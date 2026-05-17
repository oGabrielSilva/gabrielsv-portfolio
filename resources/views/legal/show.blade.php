@extends('layouts.blog')

@php
    $metaTitle = $page->title;
    $metaDescription = $page->meta_description ?: $page->title;
@endphp

@section('title', $metaTitle)
@section('description', $metaDescription)
@section('post_title', $page->title)

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $page->title,
        'description' => $metaDescription,
        'url' => route('legal.show', $page),
        'dateModified' => $page->updated_at?->toAtomString(),
        'inLanguage' => 'pt-BR',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-6">
        <header class="space-y-3">
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">
                {{ $page->title }}
            </h1>
            @if($page->updated_at)
                <p class="text-xs text-gray-500">
                    Atualizado em
                    <time datetime="{{ $page->updated_at->toAtomString() }}">
                        {{ $page->updated_at->translatedFormat('d \d\e F \d\e Y') }}
                    </time>
                </p>
            @endif
        </header>

        <div class="prose prose-invert prose-sm sm:prose-base max-w-none
                    prose-headings:text-white prose-headings:font-semibold
                    prose-a:text-bulma-primary hover:prose-a:text-bulma-primary/80
                    prose-code:text-bulma-primary prose-code:bg-neutral-800 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:before:content-none prose-code:after:content-none
                    prose-pre:bg-neutral-900 prose-pre:border prose-pre:border-neutral-700
                    prose-blockquote:border-l-bulma-primary prose-blockquote:text-gray-300">
            {!! $page->body_html !!}
        </div>
    </article>
@endsection
