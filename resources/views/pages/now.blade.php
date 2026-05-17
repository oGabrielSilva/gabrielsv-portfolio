@extends('layouts.blog')

@section('title', 'Now · No que estou focado agora')
@section('description', 'O que estou fazendo, lendo, construindo e aprendendo neste momento.')
@section('post_title', '/now')

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => '/now', 'url' => route('now')],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-10">
        <header class="space-y-3">
            <x-blog.breadcrumbs :items="[
                ['name' => 'Início', 'url' => url('/')],
                ['name' => '/now'],
            ]" />
            <h1 class="text-4xl font-bold text-white sm:text-5xl">/now</h1>
            <p class="text-lg text-gray-400">
                Inspirado pelo <a href="https://nownownow.com/about" target="_blank" rel="noopener" class="text-bulma-primary hover:underline">/now movement</a> do Derek Sivers. O que estou fazendo neste momento — atualizado quando muda algo importante.
            </p>
        </header>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Construindo</h2>
            <ul class="space-y-2 text-gray-300">
                <li>Migrando o blog do WordPress para Laravel + Filament — adicionando série de posts, busca, OG dinâmico, dashboard de stats.</li>
                <li>Expandindo a suíte de <a href="{{ route('tools.index') }}" class="text-bulma-primary hover:underline">ferramentas online</a> para devs brasileiros.</li>
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Aprendendo</h2>
            <ul class="space-y-2 text-gray-300">
                <li>Filament 5 em profundidade — workflows de admin, custom actions, widgets.</li>
                <li>SEO técnico aplicado a sites pequenos — schema.org, OG dinâmico, sitemaps segmentados.</li>
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Lendo / consumindo</h2>
            <ul class="space-y-2 text-gray-300">
                <li>Newsletters de devs brasileiros e internacionais — Laravel News, Front-end Focus.</li>
                <li>Estudos de craft em UI — Rauno Freiberg, Josh Comeau, Linear blog.</li>
            </ul>
        </section>

        <p class="border-t border-neutral-800 pt-6 text-sm text-gray-500">
            Última atualização: {{ now()->translatedFormat('F \d\e Y') }}.
        </p>
    </article>
@endsection
