<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements Feedable, HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body_html',
        'body_json',
        'reading_time',
        'kind',
        'featured',
        'series_slug',
        'series_order',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'author_id',
    ];

    protected $casts = [
        'body_json' => 'array',
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'reading_time' => 'integer',
        'series_order' => 'integer',
    ];

    protected static function booted(): void
    {
        $invalidate = function (): void {
            \Illuminate\Support\Facades\Cache::forget('site.stats.quick');
            \Illuminate\Support\Facades\Cache::forget('site.stats.full');
            \Illuminate\Support\Facades\Cache::forget('site.stats.top_categories.5');
        };
        static::saved($invalidate);
        static::deleted($invalidate);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeKind(Builder $query, string $kind): Builder
    {
        return $query->where('kind', $kind);
    }

    public function setBodyHtmlAttribute(?string $value): void
    {
        $this->attributes['body_html'] = $value;

        $text = trim(strip_tags($value ?? ''));
        $words = $text === '' ? 0 : str_word_count($text);
        $codeBlocks = substr_count((string) $value, '<pre');
        $minutes = (int) max(1, (int) ceil($words / 200) + (int) round($codeBlocks * 0.5));

        $this->attributes['reading_time'] = $minutes;
    }

    public function related(int $limit = 3): Collection
    {
        $categoryIds = $this->categories->pluck('id');
        $tagIds = $this->tags->pluck('id');

        $query = static::published()
            ->where('id', '!=', $this->id)
            ->with(['categories', 'media']);

        if ($categoryIds->isNotEmpty()) {
            $query->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds));
        }

        if ($tagIds->isNotEmpty()) {
            $query->withCount(['tags as related_tags_count' => fn ($q) => $q->whereIn('tags.id', $tagIds)])
                ->orderByDesc('related_tags_count');
        }

        return $query->orderByDesc('published_at')->limit($limit)->get();
    }

    public function previousPost(): ?self
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where('published_at', '<', $this->published_at)
            ->orderByDesc('published_at')
            ->first();
    }

    public function nextPost(): ?self
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where('published_at', '>', $this->published_at)
            ->orderBy('published_at')
            ->first();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('content');
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id((string) $this->id)
            ->title($this->title)
            ->summary($this->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($this->body_html ?? ''), 200))
            ->updated($this->updated_at)
            ->link(route('blog.show', $this))
            ->authorName($this->author?->name ?? config('site.author.name'))
            ->authorEmail(config('site.author.email'));
    }

    public static function getFeedItems()
    {
        return static::published()->orderByDesc('published_at')->limit(50)->get();
    }
}
