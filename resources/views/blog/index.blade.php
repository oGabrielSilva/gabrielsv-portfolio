@extends('layouts.blog')

@php
    $isFiltered = ! empty($currentTaxonomy);
    $pageTitle = $isFiltered
        ? ($currentTaxonomy['kind'] === 'category'
            ? 'Categoria: '.$currentTaxonomy['name']
            : 'Tag: '.$currentTaxonomy['name'])
        : 'Blog';
    $kinds = [
        null => 'Tudo',
        'essay' => 'Ensaios',
        'note' => 'Notas',
        'craft' => 'Craft',
    ];
    $currentKindLocal = $currentKind ?? null;
@endphp

@section('title', $pageTitle)
@section('description', $isFiltered
    ? 'Posts marcados com '.$currentTaxonomy['name'].' no blog de Gabriel.'
    : 'Notas, ensaios e estudos visuais sobre desenvolvimento web, Laravel, performance e o que mais aparecer.')

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs(array_values(array_filter([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => 'Blog', 'url' => route('blog.index')],
        $isFiltered ? ['name' => $currentTaxonomy['name']] : null,
    ]))), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="space-y-10">
        <header class="space-y-3">
            <x-blog.breadcrumbs :items="array_values(array_filter([
                ['name' => 'Início', 'url' => url('/')],
                $isFiltered ? ['name' => 'Blog', 'url' => route('blog.index')] : null,
                ['name' => $isFiltered ? $currentTaxonomy['name'] : 'Blog'],
            ]))" />

            <h1 class="text-3xl font-bold text-white sm:text-4xl lg:text-5xl">{{ $pageTitle }}</h1>

            @if(! $isFiltered)
                <p class="text-base text-gray-400 sm:text-lg">
                    Notas, ensaios e estudos visuais sobre desenvolvimento web. Sem filtro de calendário editorial — escrevo quando algo merece.
                </p>
            @endif
        </header>

        {{-- Featured post (só na home da listagem sem filtros) --}}
        @if($featured)
            @php $featuredCover = $featured->getFirstMediaUrl('cover'); @endphp
            <a
                href="{{ route('blog.show', $featured) }}"
                class="spotlight group block overflow-hidden rounded-2xl border border-bulma-primary/30 bg-linear-to-br from-bulma-primary/10 via-neutral-900 to-neutral-950 transition-colors hover:border-bulma-primary/60"
            >
                <div class="grid gap-0 sm:grid-cols-2">
                    @if($featuredCover)
                        <div class="aspect-video overflow-hidden sm:aspect-auto sm:h-full">
                            <img
                                src="{{ $featuredCover }}"
                                alt="{{ $featured->title }}"
                                class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                                loading="eager"
                                decoding="async"
                            >
                        </div>
                    @endif
                    <div class="flex flex-col gap-3 p-6 sm:p-8">
                        <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-bulma-primary/20 px-3 py-1 text-xs font-medium text-bulma-primary">
                            <i data-lucide="star" class="size-3"></i>
                            Em destaque
                        </span>
                        <h2 class="text-2xl font-bold leading-tight text-white transition-colors group-hover:text-bulma-primary sm:text-3xl">
                            {{ $featured->title }}
                        </h2>
                        @if($featured->excerpt)
                            <p class="text-sm leading-relaxed text-gray-400 line-clamp-3">{{ $featured->excerpt }}</p>
                        @endif
                        <div class="mt-auto flex flex-wrap items-center gap-2 text-xs text-gray-500">
                            <span>{{ $featured->reading_time ?? 1 }} min</span>
                            @if($featured->published_at)
                                <span aria-hidden="true">·</span>
                                <time datetime="{{ $featured->published_at->toAtomString() }}">
                                    {{ $featured->published_at->translatedFormat('d \d\e F \d\e Y') }}
                                </time>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @endif

        {{-- Filtros: tabs por kind + chips de categoria --}}
        @if(! $isFiltered)
            <nav aria-label="Filtros do blog" class="space-y-4">
                <div class="flex flex-wrap items-center gap-2 border-b border-neutral-800 pb-3">
                    @foreach($kinds as $key => $label)
                        @php
                            $isActive = $currentKindLocal === $key || ($key === null && $currentKindLocal === null);
                            $href = $key === null ? route('blog.index') : route('blog.index', ['kind' => $key]);
                        @endphp
                        <a
                            href="{{ $href }}"
                            @class([
                                'inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition-colors',
                                'bg-bulma-primary/20 text-bulma-primary' => $isActive,
                                'text-gray-400 hover:bg-neutral-800 hover:text-gray-200' => ! $isActive,
                            ])
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                @if($categories->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs uppercase tracking-wide text-gray-500">Categorias:</span>
                        @foreach($categories as $cat)
                            <x-blog.chip
                                :label="$cat->name"
                                :slug="$cat->slug"
                                :href="route('blog.category', $cat)"
                            />
                        @endforeach
                    </div>
                @endif
            </nav>
        @endif

        @if($isFiltered)
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-500 transition-colors hover:text-bulma-primary">
                <i data-lucide="arrow-left" class="size-3.5"></i>
                Ver todos os posts
            </a>
        @endif

        @if($posts->isEmpty())
            <div class="rounded-2xl border border-neutral-800 bg-neutral-900/40 p-10 text-center text-gray-400">
                <p>Nada por aqui ainda nesta seção.</p>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <x-blog-post-card :post="$post" />
                @endforeach
            </div>

            <div>
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
