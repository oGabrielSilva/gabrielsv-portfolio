@extends('layouts.blog')

@section('title', 'Changelog · O que mudou neste site')
@section('description', 'Histórico de features e correções publicadas neste site.')
@section('post_title', 'Changelog')

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => 'Changelog', 'url' => route('changelog')],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-8">
        <header class="space-y-3">
            <x-blog.breadcrumbs :items="[
                ['name' => 'Início', 'url' => url('/')],
                ['name' => 'Changelog'],
            ]" />
            <h1 class="text-4xl font-bold text-white sm:text-5xl">Changelog</h1>
            <p class="text-lg text-gray-400">
                O que mudou no site, extraído direto dos commits. Atualiza automaticamente.
            </p>
        </header>

        @forelse($entries as $entry)
            <article class="flex flex-col gap-2 border-l-2 border-neutral-800 pl-5 transition-colors hover:border-bulma-primary/60">
                <header class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500">
                    @php
                        $typeLabel = match($entry['type']) {
                            'feat' => ['Feature', 'bg-bulma-primary/15 text-bulma-primary border-bulma-primary/30'],
                            'fix' => ['Fix', 'bg-amber-500/15 text-amber-300 border-amber-500/30'],
                            'perf' => ['Perf', 'bg-violet-500/15 text-violet-300 border-violet-500/30'],
                            'refactor' => ['Refactor', 'bg-blue-500/15 text-blue-300 border-blue-500/30'],
                            default => [ucfirst($entry['type']), 'bg-neutral-800 text-gray-400 border-neutral-700'],
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide {{ $typeLabel[1] }}">
                        {{ $typeLabel[0] }}
                    </span>
                    @if($entry['scope'])
                        <span class="text-gray-600">·</span>
                        <span class="font-mono text-gray-400">{{ $entry['scope'] }}</span>
                    @endif
                    <span class="text-gray-700">·</span>
                    <time datetime="{{ $entry['date']->toAtomString() }}">
                        {{ $entry['date']->translatedFormat('d \d\e F \d\e Y') }}
                    </time>
                    <span class="text-gray-700">·</span>
                    <span class="font-mono text-gray-600">{{ $entry['hash'] }}</span>
                </header>
                <p class="text-gray-200">{{ $entry['message'] }}</p>
            </article>
        @empty
            <p class="text-gray-500">Sem entradas ainda — o git log será exibido aqui assim que houver commits feat/fix.</p>
        @endforelse
    </article>
@endsection
