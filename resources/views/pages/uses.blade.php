@extends('layouts.blog')

@section('title', 'Uses · As ferramentas que uso no dia a dia')
@section('description', 'Editor, terminal, hardware, fontes e libs que uso para programar todo dia.')
@section('post_title', '/uses')

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => '/uses', 'url' => route('uses')],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-10">
        <header class="space-y-3">
            <x-blog.breadcrumbs :items="[
                ['name' => 'Início', 'url' => url('/')],
                ['name' => '/uses'],
            ]" />
            <h1 class="text-4xl font-bold text-white sm:text-5xl">/uses</h1>
            <p class="text-lg text-gray-400">
                Setup atual: editor, terminal, hardware e libs que uso para escrever código todo dia. Atualizado quando troco alguma peça.
            </p>
        </header>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Editor & terminal</h2>
            <ul class="space-y-2 text-gray-300">
                <li><strong class="text-white">VS Code</strong> — extensions essenciais: PHP Intelephense, Laravel Blade, Tailwind IntelliSense, GitLens, Error Lens, ESLint.</li>
                <li><strong class="text-white">Tema:</strong> One Dark Pro / Catppuccin Mocha. Fonte: <a href="https://github.com/tonsky/FiraCode" target="_blank" rel="noopener" class="text-bulma-primary hover:underline">Fira Code</a> com ligatures.</li>
                <li><strong class="text-white">Terminal:</strong> Windows Terminal + PowerShell 7. Bash via WSL2 (Ubuntu) para tudo Docker.</li>
                <li><strong class="text-white">Atalhos VSCode preferidos:</strong> Ctrl+P, Ctrl+Shift+P, Ctrl+. (quickfix), Alt+Click (multi-cursor).</li>
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Stack que respeito</h2>
            <ul class="space-y-2 text-gray-300">
                <li><strong class="text-white">Laravel</strong> — minha casa. PHP 8.3+, Eloquent, Blade, Filament para admin.</li>
                <li><strong class="text-white">Tailwind CSS v4</strong> — design tokens via <code>@theme</code>, zero JS config.</li>
                <li><strong class="text-white">Vanilla JS</strong> — para componentes simples; <strong class="text-white">Alpine.js</strong> quando precisa de reatividade leve.</li>
                <li><strong class="text-white">MySQL</strong> em produção, <strong class="text-white">SQLite</strong> em dev local.</li>
                <li><strong class="text-white">Redis</strong> para cache e sessões.</li>
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Hardware</h2>
            <ul class="space-y-2 text-gray-300">
                <li><strong class="text-white">Notebook principal:</strong> que rode Docker sem chorar.</li>
                <li><strong class="text-white">Teclado:</strong> mecânico, switches lineares.</li>
                <li><strong class="text-white">Mouse:</strong> Logitech (qualquer um silencioso).</li>
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Browser & dev tools</h2>
            <ul class="space-y-2 text-gray-300">
                <li><strong class="text-white">Brave</strong> para navegar; <strong class="text-white">Chrome</strong> para dev (Lighthouse, DevTools).</li>
                <li><strong class="text-white">Bruno</strong> ou Postman para APIs.</li>
                <li><strong class="text-white">phpMyAdmin</strong> + <strong class="text-white">TablePlus</strong> para banco.</li>
            </ul>
        </section>

        <p class="border-t border-neutral-800 pt-6 text-sm text-gray-500">
            Inspirado pela <a href="https://uses.tech" target="_blank" rel="noopener" class="text-bulma-primary hover:underline">uses.tech</a> do Wes Bos. Atualizado em {{ now()->translatedFormat('F \d\e Y') }}.
        </p>
    </article>
@endsection
