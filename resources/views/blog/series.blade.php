@extends('layouts.blog')

@php
    $first = $posts->first();
    $title = 'Série: '.$seriesSlug;
    $description = 'Posts da série '.$seriesSlug.' — '.$posts->count().' parte'.($posts->count() === 1 ? '' : 's').'.';
@endphp

@section('title', $title)
@section('description', $description)
@section('post_title', $title)

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => 'Blog', 'url' => route('blog.index')],
        ['name' => 'Série: '.$seriesSlug, 'url' => route('blog.series', $seriesSlug)],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="space-y-8">
        <header class="space-y-3">
            <x-blog.breadcrumbs :items="[
                ['name' => 'Início', 'url' => url('/')],
                ['name' => 'Blog', 'url' => route('blog.index')],
                ['name' => 'Série: '.$seriesSlug],
            ]" />
            <p class="text-xs uppercase tracking-wide text-bulma-primary">Série</p>
            <h1 class="text-4xl font-bold text-white sm:text-5xl">{{ $seriesSlug }}</h1>
            <p class="text-lg text-gray-400">{{ $posts->count() }} parte{{ $posts->count() === 1 ? '' : 's' }} · ler na ordem</p>
        </header>

        <ol class="space-y-4">
            @foreach($posts as $i => $post)
                <li>
                    <a
                        href="{{ route('blog.show', $post) }}"
                        class="spotlight group flex flex-col gap-3 rounded-2xl border border-neutral-800 bg-neutral-900/40 p-5 transition-colors hover:border-bulma-primary/40 sm:flex-row sm:items-start sm:gap-5"
                    >
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full border border-bulma-primary/30 bg-bulma-primary/10 font-mono text-lg text-bulma-primary">
                            {{ $i + 1 }}
                        </div>

                        <div class="flex-1 space-y-2">
                            <h2 class="text-xl font-semibold leading-snug text-white transition-colors group-hover:text-bulma-primary">
                                {{ $post->title }}
                            </h2>
                            @if($post->excerpt)
                                <p class="text-sm leading-relaxed text-gray-400">{{ $post->excerpt }}</p>
                            @endif
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span>{{ $post->reading_time ?? 1 }} min</span>
                                @if($post->published_at)
                                    <span aria-hidden="true">·</span>
                                    <time datetime="{{ $post->published_at->toAtomString() }}">
                                        {{ $post->published_at->translatedFormat('d/m/Y') }}
                                    </time>
                                @endif
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
@endsection
