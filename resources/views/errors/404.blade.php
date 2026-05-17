@extends('layouts.blog')

@section('title', '404 · página perdida')
@section('description', 'Essa URL não existe. Talvez tenha sido renomeada — busca aí embaixo.')

@section('content')
    @php
        $recent = \App\Models\Post::published()->orderByDesc('published_at')->limit(5)->get(['slug', 'title']);
    @endphp

    <article class="space-y-10 text-center">
        <header class="space-y-4">
            <p class="font-mono text-sm tracking-wide text-bulma-primary">404</p>
            <h1 class="text-4xl font-bold leading-tight text-white sm:text-5xl">
                Essa página não passou no meu <a href="{{ route('tools.slugify') }}" class="text-bulma-primary hover:underline">slugify</a>.
            </h1>
            <p class="mx-auto max-w-xl text-base text-gray-400 sm:text-lg">
                Talvez o link esteja velho ou o slug tenha mudado. Dá pra abrir a busca com
                <kbd class="rounded bg-neutral-800 px-1.5 py-0.5 font-mono text-xs text-gray-300">⌘K</kbd>
                ou conferir os últimos posts abaixo.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 rounded-full bg-bulma-primary px-4 py-2 text-sm font-medium text-neutral-950 transition-colors hover:bg-bulma-primary/80">
                    <i data-lucide="home" class="size-4"></i>
                    Voltar pra home
                </a>
                <button type="button" data-command-palette-open class="inline-flex items-center gap-2 rounded-full border border-neutral-800 bg-neutral-900 px-4 py-2 text-sm text-gray-300 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary">
                    <i data-lucide="search" class="size-4"></i>
                    Buscar
                </button>
            </div>
        </header>

        @if($recent->isNotEmpty())
            <section class="space-y-3 text-left">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Últimos posts</h2>
                <ul class="space-y-2">
                    @foreach($recent as $p)
                        <li>
                            <a href="{{ route('blog.show', $p) }}" class="block rounded-xl border border-neutral-800 bg-neutral-900/40 px-4 py-3 text-sm text-gray-200 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary">
                                {{ $p->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </article>
@endsection
