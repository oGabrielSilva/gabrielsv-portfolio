@extends('layouts.blog')

@php
    $cover = $post->getFirstMediaUrl('cover');
    $metaTitle = $post->meta_title ?: $post->title;
    $metaDescription = $post->meta_description ?: $post->excerpt ?: 'Post de ' . config('app.name', 'Gabriel');
@endphp

@section('title', $metaTitle)
@section('description', $metaDescription)
@section('post_title', $post->title)
@section('og_type', 'article')
@if($cover)
    @section('og_image', $cover)
@endif

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $metaDescription,
        'image' => $cover ?: null,
        'datePublished' => $post->published_at?->toAtomString(),
        'dateModified' => $post->updated_at?->toAtomString(),
        'author' => $post->author ? [
            '@type' => 'Person',
            'name' => $post->author->name,
        ] : null,
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => route('blog.show', $post),
        ],
        'inLanguage' => 'pt-BR',
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-6">
        <header class="space-y-4">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500">
                @if($post->published_at)
                    <time datetime="{{ $post->published_at->toAtomString() }}">
                        {{ $post->published_at->translatedFormat('d \d\e F \d\e Y') }}
                    </time>
                @endif
                @foreach($post->categories as $category)
                    <a href="{{ route('blog.category', $category) }}"
                        class="inline-flex items-center gap-1 hover:text-bulma-primary transition-colors">
                        <i data-lucide="folder" class="w-3 h-3"></i>
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">
                {{ $post->title }}
            </h1>
            @if($post->excerpt)
                <p class="text-base sm:text-lg text-gray-400">{{ $post->excerpt }}</p>
            @endif
        </header>

        @if($cover)
            <div class="rounded-xl overflow-hidden border border-neutral-700/50">
                <img src="{{ $cover }}" alt="{{ $post->title }}" class="w-full h-auto">
            </div>
        @endif

        <div class="prose prose-invert prose-sm sm:prose-base max-w-none
                    prose-headings:text-white prose-headings:font-semibold
                    prose-a:text-bulma-primary hover:prose-a:text-bulma-primary/80
                    prose-code:text-bulma-primary prose-code:bg-neutral-800 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:before:content-none prose-code:after:content-none
                    prose-pre:bg-neutral-900 prose-pre:border prose-pre:border-neutral-700
                    prose-blockquote:border-l-bulma-primary prose-blockquote:text-gray-300
                    prose-img:rounded-lg prose-img:border prose-img:border-neutral-700/50">
            {!! $post->body_html !!}
        </div>

        @if($post->tags->isNotEmpty())
            <footer class="pt-6 border-t border-neutral-700/50">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Tags</span>
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}"
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full bg-neutral-800 border border-neutral-700 text-gray-300 hover:border-bulma-primary/40 hover:text-bulma-primary transition-colors">
                            <i data-lucide="tag" class="w-3 h-3"></i>
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </footer>
        @endif
    </article>
@endsection
