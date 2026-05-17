<?php

namespace App\Utils;

use App\Models\Category;
use App\Models\PageView;
use App\Models\Post;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Cache;

class SiteStats
{
    /**
     * Métricas rápidas usadas no footer/strip "alive".
     *
     * @return array{posts_total: int, tools_total: int, last_post_at: ?\Illuminate\Support\Carbon, last_post_days_ago: ?int}
     */
    public static function quickStats(): array
    {
        return Cache::remember('site.stats.quick', now()->addHour(), function (): array {
            $lastPost = Post::published()->orderByDesc('published_at')->first(['published_at']);

            return [
                'posts_total' => Post::published()->count(),
                'tools_total' => count(AppServiceProvider::TOOLS),
                'last_post_at' => $lastPost?->published_at,
                'last_post_days_ago' => $lastPost?->published_at?->diffInDays(now()),
            ];
        });
    }

    /**
     * Top N categorias para footer/sidebars (cache 6h).
     */
    public static function topCategories(int $limit = 5)
    {
        return Cache::remember('site.stats.top_categories.'.$limit, now()->addHours(6), function () use ($limit) {
            return Category::query()
                ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Estatísticas completas para /stats.
     */
    public static function fullStats(): array
    {
        return Cache::remember('site.stats.full', now()->addHour(), function (): array {
            $quick = static::quickStats();

            $postsThisYear = Post::published()
                ->whereYear('published_at', now()->year)
                ->count();

            $totalReadingMinutes = (int) Post::published()->sum('reading_time');
            $estimatedWords = $totalReadingMinutes * 200;

            $visitsThisMonth = PageView::query()
                ->where('viewed_at', '>=', now()->startOfMonth())
                ->where('is_bot', false)
                ->count();

            $topPosts = PageView::query()
                ->where('viewed_at', '>=', now()->subDays(30))
                ->where('is_bot', false)
                ->where('path', 'like', '/b/%')
                ->selectRaw('path, COUNT(*) as views')
                ->groupBy('path')
                ->orderByDesc('views')
                ->limit(5)
                ->get()
                ->map(function ($row) {
                    $slug = ltrim(str_replace('/b/', '', $row->path), '/');
                    $post = Post::where('slug', $slug)->first(['slug', 'title']);

                    return [
                        'title' => $post?->title ?? $slug,
                        'slug' => $slug,
                        'views' => (int) $row->views,
                    ];
                })
                ->filter(fn ($row) => $row['title'] !== '');

            return array_merge($quick, [
                'posts_this_year' => $postsThisYear,
                'reading_minutes_total' => $totalReadingMinutes,
                'estimated_words' => $estimatedWords,
                'visits_this_month' => $visitsThisMonth,
                'top_posts' => $topPosts->values(),
                'last_deploy_commit' => substr((string) config('app.commit', ''), 0, 7),
            ]);
        });
    }
}
