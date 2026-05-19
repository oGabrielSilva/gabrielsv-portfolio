<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">

<head>
    @include('partials.head')

    @push('jsonld')
        <script type="application/ld+json">
        {!! json_encode(app(\App\Services\JsonLdBuilder::class)->forPerson(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        <script type="application/ld+json">
        {!! json_encode(app(\App\Services\JsonLdBuilder::class)->forWebsite(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush
</head>

<body class="antialiased text-gray-300 max-w-dvw overflow-x-hidden w-dvw">

    <header>
        <nav class="w-dvw py-4 sm:py-6 fixed top-0 z-50 bg-neutral-900/90 backdrop-blur-md border-b border-white/5">
            <div class="max-w-full mx-auto px-4 sm:px-6 flex justify-between items-center">
                <a href="{{ url('/') }}"
                    class="text-xl font-bold text-white tracking-tight hover:text-bulma-primary transition-colors">
                    <x-site-logo class="w-8 h-8 inline-block" />
                </a>

                <div class="hidden md:flex items-center gap-6 lg:gap-8">
                    <a href="{{ url('/#sobre') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Sobre</a>
                    <a href="{{ url('/#servicos') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Serviços</a>
                    <a href="{{ url('/#blog') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Artigos</a>
                    <a href="{{ route('tools.index') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Ferramentas</a>
                    <a href="{{ route('blog.index') }}"
                        class="text-sm font-medium text-bulma-primary hover:text-bulma-primary/80 transition-colors inline-flex items-center gap-1.5">
                        <span>Blog</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                    <button type="button" data-command-palette-open
                        class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900 px-2.5 py-1 text-xs text-gray-400 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary"
                        aria-label="Abrir busca">
                        <i data-lucide="search" class="size-3"></i>
                        <span class="hidden lg:inline">Buscar</span>
                        <kbd class="hidden font-mono text-[10px] text-gray-600 lg:inline">⌘K</kbd>
                    </button>
                </div>

                <button id="mobile-menu-btn" type="button"
                    class="md:hidden p-2 w-10 h-10 flex items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Abrir menu principal">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path>
                    </svg>
                </button>
            </div>

            <div id="mobile-menu"
                class="absolute top-full left-0 w-full bg-neutral-800 border-b border-neutral-700 overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0 md:hidden">
                <div class="px-4 py-4 flex flex-col gap-3">
                    <a href="{{ url('/#sobre') }}"
                        class="text-gray-300 hover:text-white transition-colors block py-1">Sobre</a>
                    <a href="{{ url('/#servicos') }}"
                        class="text-gray-300 hover:text-white transition-colors block py-1">Serviços</a>
                    <a href="{{ url('/#blog') }}"
                        class="text-gray-300 hover:text-white transition-colors block py-1">Artigos</a>
                    <a href="{{ route('tools.index') }}"
                        class="text-gray-300 hover:text-white transition-colors block py-1">Ferramentas</a>
                    <div class="border-t border-neutral-700 pt-3 mt-1">
                        <a href="{{ route('blog.index') }}"
                            class="text-bulma-primary font-medium block py-1">Ir para o Blog</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main id="top" class="max-w-5xl mx-auto px-4 sm:px-6 pt-24 sm:pt-32 pb-16 sm:pb-32">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.command-palette')
</body>

</html>
