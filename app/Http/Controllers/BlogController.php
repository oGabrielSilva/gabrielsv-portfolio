<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    private const PER_PAGE = 10;

    public function index(): View
    {
        $posts = Post::published()
            ->with(['categories', 'tags', 'media'])
            ->orderByDesc('published_at')
            ->paginate(self::PER_PAGE);

        return view('blog.index', [
            'posts' => $posts,
            'currentTaxonomy' => null,
        ]);
    }

    public function byCategory(Category $category): View
    {
        $posts = $category->posts()
            ->published()
            ->with(['categories', 'tags', 'media'])
            ->orderByDesc('published_at')
            ->paginate(self::PER_PAGE);

        return view('blog.index', [
            'posts' => $posts,
            'currentTaxonomy' => [
                'kind' => 'category',
                'name' => $category->name,
            ],
        ]);
    }

    public function byTag(Tag $tag): View
    {
        $posts = $tag->posts()
            ->published()
            ->with(['categories', 'tags', 'media'])
            ->orderByDesc('published_at')
            ->paginate(self::PER_PAGE);

        return view('blog.index', [
            'posts' => $posts,
            'currentTaxonomy' => [
                'kind' => 'tag',
                'name' => $tag->name,
            ],
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->status === 'published'
                && $post->published_at !== null
                && $post->published_at->lte(now()),
            404,
        );

        $post->load(['categories', 'tags', 'author', 'media']);

        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
