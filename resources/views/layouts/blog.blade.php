<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <script src="https://unpkg.com/lucide@0.544.0"></script>
    @include('partials.head')
    @stack('styles')

    @push('jsonld')
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Blog',
            'name' => config('app.name', 'Gabriel') . ' — Blog',
            'url' => route('blog.index'),
            'inLanguage' => 'pt-BR',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush
</head>

<body class="antialiased text-gray-300 max-w-dvw overflow-x-hidden w-dvw">
    <header>
        <nav class="w-dvw py-3 sm:py-4 fixed top-0 z-50 bg-neutral-900/90 backdrop-blur-md border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 sm:gap-4 min-w-0 flex-1">
                    <a href="{{ url('/') }}"
                        class="shrink-0 text-xl font-bold text-white tracking-tight hover:text-bulma-primary transition-colors">
                        <img src="/favicon.svg" alt="Logo" class="w-7 h-7 sm:w-8 sm:h-8 inline-block">
                    </a>
                    <span class="text-gray-600 hidden sm:inline">/</span>
                    <a href="{{ route('blog.index') }}"
                        class="hidden sm:inline text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        Blog
                    </a>
                    @hasSection('post_title')
                        <span class="text-gray-600 hidden sm:inline">/</span>
                        <span class="text-sm font-medium text-white truncate hidden sm:inline">@yield('post_title')</span>
                    @endif
                </div>

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-4 lg:gap-6">
                    <a href="{{ url('/') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1.5">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        <span>Início</span>
                    </a>
                    <a href="{{ route('tools.index') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1.5">
                        <i data-lucide="wrench" class="w-4 h-4"></i>
                        <span>Ferramentas</span>
                    </a>
                </div>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" type="button"
                    class="md:hidden p-2 w-10 h-10 flex items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div id="mobile-menu"
                class="absolute top-full left-0 w-full bg-neutral-800 border-b border-neutral-700 overflow-y-auto transition-all duration-300 ease-in-out opacity-0 md:hidden max-h-0">
                <div class="px-4 py-4 space-y-2">
                    <a href="{{ url('/') }}"
                        class="text-gray-300 hover:text-white transition-colors py-2 inline-flex items-center gap-2">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        Início
                    </a>
                    <a href="{{ route('blog.index') }}"
                        class="text-gray-300 hover:text-white transition-colors py-2 inline-flex items-center gap-2">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        Blog
                    </a>
                    <a href="{{ route('tools.index') }}"
                        class="text-gray-300 hover:text-white transition-colors py-2 inline-flex items-center gap-2">
                        <i data-lucide="wrench" class="w-4 h-4"></i>
                        Ferramentas
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="pt-14 sm:pt-16 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-10">
            @yield('content')
        </div>
    </main>

    @include('partials.footer')

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
