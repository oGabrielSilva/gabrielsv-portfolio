<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Providers\AppServiceProvider;
use App\Services\MarkupRenderer;
use App\Services\TableOfContentsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): View
    {
        $kind = $request->query('kind');
        $kind = in_array($kind, ['essay', 'note', 'craft'], true) ? $kind : null;

        $query = Post::published()->with(['categories', 'tags', 'media']);

        if ($kind) {
            $query->where('kind', $kind);
        }

        $featured = null;
        if (! $kind && $request->page === null) {
            $featured = Post::published()
                ->featured()
                ->with(['categories', 'tags', 'media'])
                ->orderByDesc('published_at')
                ->first();

            if ($featured) {
                $query->where('id', '!=', $featured->id);
            }
        }

        $posts = $query->orderByDesc('published_at')->paginate(self::PER_PAGE);

        $categories = Category::query()
            ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('blog.index', [
            'posts' => $posts,
            'currentTaxonomy' => null,
            'currentKind' => $kind,
            'featured' => $featured,
            'categories' => $categories,
        ]);
    }

    public function byCategory(Category $category): View
    {
        $posts = $category->posts()
            ->published()
            ->with(['categories', 'tags', 'media'])
            ->orderByDesc('published_at')
            ->paginate(self::PER_PAGE);

        $categories = Category::query()
            ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('blog.index', [
            'posts' => $posts,
            'currentTaxonomy' => [
                'kind' => 'category',
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'currentKind' => null,
            'featured' => null,
            'categories' => $categories,
        ]);
    }

    public function byTag(Tag $tag): View
    {
        $posts = $tag->posts()
            ->published()
            ->with(['categories', 'tags', 'media'])
            ->orderByDesc('published_at')
            ->paginate(self::PER_PAGE);

        $categories = Category::query()
            ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('blog.index', [
            'posts' => $posts,
            'currentTaxonomy' => [
                'kind' => 'tag',
                'name' => $tag->name,
                'slug' => $tag->slug,
            ],
            'currentKind' => null,
            'featured' => null,
            'categories' => $categories,
        ]);
    }

    public function bySeries(string $slug): View
    {
        $posts = Post::published()
            ->where('series_slug', $slug)
            ->orderBy('series_order')
            ->orderBy('published_at')
            ->with(['categories', 'tags', 'media'])
            ->get();

        abort_if($posts->isEmpty(), 404);

        return view('blog.series', [
            'seriesSlug' => $slug,
            'posts' => $posts,
        ]);
    }

    public function show(Post $post, TableOfContentsService $tocService, MarkupRenderer $renderer): View
    {
        $isPublished = $post->status === 'published'
            && $post->published_at !== null
            && $post->published_at->lte(now());

        // Só o autor do post pode pré-visualizar o próprio rascunho. Checar o
        // autor (em vez de só auth()->check()) evita que um futuro usuário
        // autenticado não-admin (ex.: newsletter logada) enxergue rascunhos.
        // Hoje só o admin existe e ele é o autor dos posts. A view marca essas
        // páginas como noindex e exibe um banner de rascunho.
        $isDraftPreview = ! $isPublished
            && auth()->check()
            && (int) $post->author_id === (int) auth()->id();

        abort_unless($isPublished || $isDraftPreview, 404);

        $post->load(['categories', 'tags', 'author', 'media']);

        $tocResult = $tocService->extract($post->body_html);
        $renderedHtml = $renderer->render($tocResult['html']);

        // Só referencia o bundle do Chart.js quando o post tem gráfico. Olha o
        // HTML já renderizado (<figure class="chart">), porque o marcador pode
        // ter virado code block no editor e o body_html não ter mais 'data-chart'.
        $hasChart = str_contains($renderedHtml, '<figure class="chart"');

        // Só referencia o highlight.js quando há bloco de código com linguagem
        // (o MarkupRenderer emite class="language-X" nesses casos).
        $hasCode = str_contains($renderedHtml, 'class="language-');

        $seriesPosts = $post->series_slug
            ? Post::published()
                ->where('series_slug', $post->series_slug)
                ->orderBy('series_order')
                ->orderBy('published_at')
                ->get(['id', 'slug', 'title', 'series_order'])
            : collect();

        return view('blog.show', [
            'post' => $post,
            'renderedHtml' => $renderedHtml,
            'toc' => $tocResult['toc'],
            'related' => $post->related(3),
            'previous' => $post->previousPost(),
            'next' => $post->nextPost(),
            'seriesPosts' => $seriesPosts,
            'hasChart' => $hasChart,
            'hasCode' => $hasCode,
            'isDraftPreview' => $isDraftPreview,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $posts = Post::published()
            ->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('body_html', 'like', "%{$q}%");
            })
            ->orderByDesc('published_at')
            ->limit(8)
            ->get(['slug', 'title', 'excerpt', 'kind'])
            ->map(fn ($p) => [
                'type' => 'post',
                'title' => $p->title,
                'subtitle' => Str::limit(strip_tags((string) $p->excerpt), 80),
                'url' => route('blog.show', $p),
                'badge' => $p->kind,
            ]);

        $tools = collect(AppServiceProvider::TOOLS)
            ->filter(function ($t) use ($q) {
                $haystack = strtolower($t['name'].' '.($t['description'] ?? ''));

                return str_contains($haystack, strtolower($q));
            })
            ->take(5)
            ->map(fn ($t) => [
                'type' => 'tool',
                'title' => $t['name'],
                'subtitle' => $t['description'] ?? '',
                'url' => route($t['route']),
                'badge' => 'tool',
            ])
            ->values();

        return response()->json(['results' => $posts->concat($tools)->values()]);
    }
}
