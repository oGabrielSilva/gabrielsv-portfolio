@extends('layouts.blog')

@php
    $metaTitle = $page->title;
    $metaDescription = $page->meta_description ?: ($page->subtitle ?: $page->title);
@endphp

@section('title', $metaTitle)
@section('description', $metaDescription)
@section('post_title', $page->title)

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => $page->title, 'url' => url('/'.$page->slug)],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-8">
        <x-blog.breadcrumbs :items="[
            ['name' => 'Início', 'url' => url('/')],
            ['name' => $page->title],
        ]" />

        <header class="space-y-3">
            <h1 class="text-4xl font-bold leading-tight text-white sm:text-5xl">
                /{{ $page->slug }}
            </h1>
            @if($page->subtitle)
                <p class="text-lg leading-relaxed text-gray-400 sm:text-xl">
                    {{ $page->subtitle }}
                </p>
            @endif
        </header>

        <div class="prose prose-invert prose-sm sm:prose-base max-w-none
                    prose-headings:text-white prose-headings:font-semibold
                    prose-a:text-bulma-primary prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-white
                    prose-code:text-bulma-primary prose-code:bg-neutral-800/80 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:font-normal prose-code:before:content-none prose-code:after:content-none
                    prose-blockquote:border-l-bulma-primary prose-blockquote:text-gray-300 prose-blockquote:not-italic
                    prose-img:rounded-xl prose-img:border prose-img:border-neutral-800">
            {!! $page->body_html !!}
        </div>

        <p class="border-t border-neutral-800 pt-6 text-xs text-gray-500">
            Última atualização:
            <time datetime="{{ $page->updated_at->toAtomString() }}">
                {{ $page->updated_at->translatedFormat('d \d\e F \d\e Y') }}
            </time>.
        </p>
    </article>
@endsection
