<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['slug', 'name', 'description'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    protected static function booted(): void
    {
        $invalidate = function (): void {
            \Illuminate\Support\Facades\Cache::forget('site.stats.top_categories.5');
        };
        static::saved($invalidate);
        static::deleted($invalidate);
    }
}
