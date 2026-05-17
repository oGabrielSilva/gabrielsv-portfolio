@extends('layouts.blog')

@php
    $cover = $post->getFirstMediaUrl('cover');
    $ogImage = $cover !== '' ? $cover : route('og.post', $post);
    $metaTitle = $post->meta_title ?: $post->title;
    $metaDescription = $post->meta_description ?: $post->excerpt ?: $post->title;
    $primaryCategory = $post->categories->first();
@endphp

@section('title', $metaTitle)
@section('description', $metaDescription)
@section('post_title', $post->title)
@section('og_type', 'article')
@section('og_image', $ogImage)

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->forPost($post), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs(array_values(array_filter([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => 'Blog', 'url' => route('blog.index')],
        $primaryCategory ? ['name' => $primaryCategory->name, 'url' => route('blog.category', $primaryCategory)] : null,
        ['name' => $post->title, 'url' => route('blog.show', $post)],
    ]))), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    {{-- Reading progress bar --}}
    <div id="reading-progress" class="pointer-events-none fixed left-0 top-0 z-50 h-0.75 w-0 bg-bulma-primary transition-[width] duration-100 ease-out" aria-hidden="true"></div>

    <article class="space-y-8">
        {{-- Breadcrumbs --}}
        <x-blog.breadcrumbs :items="array_values(array_filter([
            ['name' => 'Início', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog.index')],
            $primaryCategory ? ['name' => $primaryCategory->name, 'url' => route('blog.category', $primaryCategory)] : null,
            ['name' => $post->title],
        ]))" />

        {{-- Header --}}
        <header class="space-y-4">
            @if($post->categories->isNotEmpty() || ($post->kind && $post->kind !== 'essay'))
                <div class="flex flex-wrap items-center gap-2">
                    @if($post->kind && $post->kind !== 'essay')
                        @php
                            $kindLabel = config('site.kind_labels.'.$post->kind, ucfirst($post->kind));
                            $kindColor = config('site.kind_colors.'.$post->kind, '#9ca3af');
                        @endphp
                        <x-blog.chip :label="$kindLabel" :color="$kindColor" />
                    @endif
                    @foreach($post->categories as $cat)
                        <x-blog.chip
                            :label="$cat->name"
                            :slug="$cat->slug"
                            :href="route('blog.category', $cat)"
                        />
                    @endforeach
                </div>
            @endif

            <h1 class="text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">
                {{ $post->title }}
            </h1>

            <x-blog.author-meta :post="$post" />
        </header>

        {{-- Cover --}}
        @if($cover)
            <figure class="overflow-hidden rounded-2xl border border-neutral-800 bg-neutral-900">
                <img
                    src="{{ $cover }}"
                    alt="{{ $post->title }}"
                    class="aspect-video w-full object-cover"
                    loading="eager"
                    decoding="async"
                    fetchpriority="high"
                >
            </figure>
        @endif

        {{-- Series nav (se houver) --}}
        <x-blog.series-nav :post="$post" :series-posts="$seriesPosts" />

        {{-- Content + TOC --}}
        <div class="grid gap-8 lg:grid-cols-[1fr_220px]">
            <div
                class="prose prose-invert prose-sm sm:prose-base max-w-none
                       prose-headings:text-white prose-headings:font-semibold prose-headings:scroll-mt-24
                       prose-a:text-bulma-primary prose-a:no-underline hover:prose-a:underline
                       prose-strong:text-white
                       prose-code:text-bulma-primary prose-code:bg-neutral-800/80 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:font-normal prose-code:before:content-none prose-code:after:content-none
                       prose-blockquote:border-l-bulma-primary prose-blockquote:text-gray-300 prose-blockquote:not-italic
                       prose-img:rounded-xl prose-img:border prose-img:border-neutral-800
                       prose-pre:p-0 prose-pre:bg-transparent prose-pre:border-0
                       post-content"
            >
                {!! $renderedHtml !!}
            </div>

            @if(count($toc) >= 2)
                <div class="order-first lg:order-last">
                    <x-blog.toc :toc="$toc" />
                </div>
            @endif
        </div>

        {{-- Tags --}}
        @if($post->tags->isNotEmpty())
            <div class="flex flex-wrap items-center gap-2 border-t border-neutral-800 pt-6">
                <span class="text-xs uppercase tracking-wide text-gray-500">Tags:</span>
                @foreach($post->tags as $tag)
                    <a
                        href="{{ route('blog.tag', $tag) }}"
                        class="inline-flex items-center gap-1 rounded-md bg-neutral-900 px-2 py-1 text-xs text-gray-400 transition-colors hover:bg-neutral-800 hover:text-bulma-primary"
                    >
                        <span class="text-gray-600">#</span>{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Share --}}
        <x-blog.share-buttons :post="$post" />

        {{-- Author box --}}
        <x-blog.author-box :author="$post->author" />

        {{-- Related --}}
        <x-blog.related-posts :posts="$related" />

        {{-- Prev/Next --}}
        <x-blog.prev-next :previous="$previous" :next="$next" />
    </article>
@endsection
