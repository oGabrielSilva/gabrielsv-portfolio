@extends('layouts.blog')

@section('title', 'Stats · Métricas em tempo real')
@section('description', 'Dashboard público de métricas: posts, visitas, palavras escritas, top posts.')
@section('post_title', 'Stats')

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => 'Stats', 'url' => route('stats')],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-10">
        <header class="space-y-3">
            <x-blog.breadcrumbs :items="[
                ['name' => 'Início', 'url' => url('/')],
                ['name' => 'Stats'],
            ]" />
            <h1 class="text-4xl font-bold text-white sm:text-5xl">Stats</h1>
            <p class="text-lg text-gray-400">
                Tudo que dá pra medir sobre este site, sem cookies nem tracking de terceiros. Cache de 1 hora.
            </p>
        </header>

        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-neutral-800 bg-neutral-900/40 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">Posts publicados</p>
                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['posts_total'] }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $stats['posts_this_year'] }} em {{ now()->year }}</p>
            </div>

            <div class="rounded-2xl border border-neutral-800 bg-neutral-900/40 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">Último post</p>
                <p class="mt-2 text-4xl font-bold text-white">
                    @if($stats['last_post_at'])
                        @if($stats['last_post_days_ago'] < 1)
                            hoje
                        @else
                            há {{ (int) $stats['last_post_days_ago'] }}d
                        @endif
                    @else
                        —
                    @endif
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    @if($stats['last_post_at'])
                        {{ $stats['last_post_at']->translatedFormat('d \d\e F') }}
                    @endif
                </p>
            </div>

            <div class="rounded-2xl border border-neutral-800 bg-neutral-900/40 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">Palavras escritas (est.)</p>
                <p class="mt-2 text-4xl font-bold text-white">{{ number_format($stats['estimated_words'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $stats['reading_minutes_total'] }} min de leitura no total</p>
            </div>

            <div class="rounded-2xl border border-neutral-800 bg-neutral-900/40 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">Ferramentas online</p>
                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['tools_total'] }}</p>
                <p class="mt-1 text-xs text-gray-500"><a href="{{ route('tools.index') }}" class="text-bulma-primary hover:underline">Ver todas</a></p>
            </div>

            <div class="rounded-2xl border border-neutral-800 bg-neutral-900/40 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">Visitas no mês</p>
                <p class="mt-2 text-4xl font-bold text-white">{{ number_format($stats['visits_this_month'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-gray-500">Humanos · sem bots</p>
            </div>

            <div class="rounded-2xl border border-neutral-800 bg-neutral-900/40 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">Última build</p>
                <p class="mt-2 font-mono text-2xl font-bold text-white">
                    {{ $stats['last_deploy_commit'] ?: '—' }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Commit em produção</p>
            </div>
        </section>

        @if($stats['top_posts']->isNotEmpty())
            <section class="space-y-3">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Top 5 posts (últimos 30 dias)</h2>
                <ol class="space-y-2">
                    @foreach($stats['top_posts'] as $i => $row)
                        <li class="flex items-center justify-between gap-4 rounded-xl border border-neutral-800 bg-neutral-900/30 px-4 py-3">
                            <span class="flex items-center gap-3">
                                <span class="font-mono text-xs text-gray-500">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <a href="/b/{{ $row['slug'] }}" class="text-sm font-medium text-white transition-colors hover:text-bulma-primary">
                                    {{ $row['title'] }}
                                </a>
                            </span>
                            <span class="font-mono text-xs text-gray-500">{{ number_format($row['views'], 0, ',', '.') }} views</span>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        <p class="border-t border-neutral-800 pt-6 text-xs text-gray-500">
            Métricas cacheadas por 1 hora. Coleta cookieless. Veja <a href="{{ route('legal.show', 'privacidade') }}" class="text-bulma-primary hover:underline">como tratamos privacidade</a>.
        </p>
    </article>
@endsection
