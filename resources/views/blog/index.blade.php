@extends('layouts.blog')

@php
    $isFiltered = ! empty($currentTaxonomy);
    $pageTitle = $isFiltered
        ? ($currentTaxonomy['kind'] === 'category'
            ? 'Categoria: ' . $currentTaxonomy['name']
            : 'Tag: ' . $currentTaxonomy['name'])
        : 'Blog';
@endphp

@section('title', $pageTitle)
@section('description', $isFiltered
    ? 'Posts marcados com ' . $currentTaxonomy['name'] . ' no blog de Gabriel.'
    : 'Notas, experiências e pensamentos sobre desenvolvimento, ferramentas e o que mais aparecer no caminho.')

@section('content')
    <div class="space-y-8">
        <header class="space-y-2">
            @if($isFiltered)
                <a href="{{ route('blog.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-300 transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    Todos os posts
                </a>
            @endif
            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $pageTitle }}</h1>
            @if(! $isFiltered)
                <p class="text-gray-400 text-sm sm:text-base">
                    Notas, experiências e pensamentos sobre desenvolvimento e o que mais aparecer no caminho.
                </p>
            @endif
        </header>

        @if($posts->isEmpty())
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6 text-center text-gray-400">
                Nada por aqui ainda.
            </div>
        @else
            <div class="space-y-4">
                @foreach($posts as $post)
                    @php
                        $cover = $post->getFirstMediaUrl('cover');
                    @endphp
                    <article class="group bg-neutral-800/50 border border-neutral-700/50 rounded-xl overflow-hidden hover:border-bulma-primary/40 transition-colors">
                        <a href="{{ route('blog.show', $post) }}" class="flex flex-col sm:flex-row">
                            @if($cover)
                                <div class="sm:w-48 shrink-0 aspect-video sm:aspect-square overflow-hidden bg-neutral-900">
                                    <img src="{{ $cover }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform"
                                        loading="lazy">
                                </div>
                            @endif
                            <div class="flex-1 p-4 sm:p-5 space-y-2">
                                <h2 class="text-lg sm:text-xl font-semibold text-white group-hover:text-bulma-primary transition-colors">
                                    {{ $post->title }}
                                </h2>
                                @if($post->excerpt)
                                    <p class="text-sm text-gray-400 line-clamp-2">{{ $post->excerpt }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 pt-1">
                                    @if($post->published_at)
                                        <time datetime="{{ $post->published_at->toAtomString() }}">
                                            {{ $post->published_at->translatedFormat('d \d\e F \d\e Y') }}
                                        </time>
                                    @endif
                                    @foreach($post->categories as $category)
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="folder" class="w-3 h-3"></i>
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <div>
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
