<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LegalPage;
use App\Models\Post;
use App\Models\Tag;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $now = now()->toAtomString();
        $sitemaps = [
            ['loc' => url('/sitemap-pages.xml'), 'lastmod' => $now],
            ['loc' => url('/sitemap-posts.xml'), 'lastmod' => Post::published()->max('updated_at') ?? $now],
            ['loc' => url('/sitemap-tools.xml'), 'lastmod' => $now],
            ['loc' => url('/sitemap-categories.xml'), 'lastmod' => Category::query()->max('updated_at') ?? $now],
            ['loc' => url('/sitemap-tags.xml'), 'lastmod' => Tag::query()->max('updated_at') ?? $now],
        ];

        return response(view('sitemap-index', ['sitemaps' => $sitemaps])->render(), 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    public function pages(): Response
    {
        $now = now()->toAtomString();
        $urls = collect([
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly', 'lastmod' => $now],
            ['loc' => route('blog.index'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $now],
            ['loc' => route('tools.index'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $now],
            ['loc' => route('about'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => $now],
            ['loc' => route('uses'), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => $now],
            ['loc' => route('now'), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => $now],
            ['loc' => route('stats'), 'priority' => '0.4', 'changefreq' => 'daily', 'lastmod' => $now],
            ['loc' => route('brand-guide'), 'priority' => '0.4', 'changefreq' => 'monthly', 'lastmod' => $now],
            ['loc' => route('card-generator'), 'priority' => '0.4', 'changefreq' => 'monthly', 'lastmod' => $now],
        ]);

        foreach (LegalPage::all() as $page) {
            $urls->push([
                'loc' => route('legal.show', $page),
                'priority' => '0.3',
                'changefreq' => 'yearly',
                'lastmod' => $page->updated_at?->toAtomString() ?? $now,
            ]);
        }

        return $this->xml($urls);
    }

    public function posts(): Response
    {
        $urls = Post::published()
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at'])
            ->map(fn ($p) => [
                'loc' => route('blog.show', $p),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => $p->updated_at?->toAtomString(),
            ]);

        return $this->xml($urls);
    }

    public function tools(): Response
    {
        $now = now()->toAtomString();
        $urls = collect(AppServiceProvider::TOOLS)->map(fn ($tool) => [
            'loc' => route($tool['route']),
            'priority' => '0.8',
            'changefreq' => 'monthly',
            'lastmod' => $now,
        ]);

        return $this->xml($urls);
    }

    public function categories(): Response
    {
        $urls = Category::query()
            ->whereHas('posts', fn ($q) => $q->where('status', 'published'))
            ->get(['slug', 'updated_at'])
            ->map(fn ($c) => [
                'loc' => route('blog.category', $c),
                'priority' => '0.6',
                'changefreq' => 'weekly',
                'lastmod' => $c->updated_at?->toAtomString(),
            ]);

        return $this->xml($urls);
    }

    public function tags(): Response
    {
        $urls = Tag::query()
            ->whereHas('posts', fn ($q) => $q->where('status', 'published'))
            ->get(['slug', 'updated_at'])
            ->map(fn ($t) => [
                'loc' => route('blog.tag', $t),
                'priority' => '0.5',
                'changefreq' => 'weekly',
                'lastmod' => $t->updated_at?->toAtomString(),
            ]);

        return $this->xml($urls);
    }

    private function xml($urls): Response
    {
        return response(
            view('sitemap', ['urls' => $urls, 'lastmod' => now()->toAtomString()])->render(),
            200,
            ['Content-Type' => 'application/xml; charset=utf-8'],
        );
    }
}
