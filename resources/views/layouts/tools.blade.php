<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ferramentas') - {{ config('app.name', 'Gabriel') }}</title>
    <meta name="description"
        content="@yield('description', 'Ferramentas online gratuitas para desenvolvedores e uso geral')">

    <script src="https://kit.fontawesome.com/78b3364728.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Gabriel" />
    <link rel="manifest" href="/site.webmanifest" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>

<body class="antialiased text-gray-300 bg-[#121212] overflow-x-hidden w-full">
    {{-- Header --}}
    <header class="fixed top-0 z-50 w-full bg-[#1a1a1a]/90 backdrop-blur-md border-b border-white/5">
        <nav class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">

            {{-- Esquerda: Logo + Botão Sidebar Mobile --}}
            <div class="flex items-center gap-3">
                {{-- Botão para abrir o Offcanvas (Sidebar de Ferramentas) no Mobile --}}
                <button type="button"
                    class="lg:hidden p-2 inline-flex justify-center items-center gap-2 rounded-lg border border-neutral-700 font-medium bg-neutral-800 text-gray-400 shadow-sm align-middle hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-800 focus:ring-bulma-primary transition-all text-sm"
                    data-hs-overlay="#sidebar-tools">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>

                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="/favicon.svg" alt="Logo" class="w-8 h-8">
                </a>

                {{-- Breadcrumbs (Escondido em telas muito pequenas) --}}
                <div class="hidden sm:flex items-center gap-2 text-sm ml-2">
                    <span class="text-gray-600">/</span>
                    <a href="{{ route('tools.index') }}"
                        class="text-gray-400 hover:text-white transition-colors">Ferramentas</a>
                    @hasSection('tool_name')
                        <span class="text-gray-600">/</span>
                        <span class="text-white font-medium truncate max-w-[100px] md:max-w-none">@yield('tool_name')</span>
                    @endif
                </div>
            </div>

            {{-- Direita: Links e Menu Mobile Site --}}
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-6 mr-4">
                    <a href="{{ url('/') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Início</a>
                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL() }}" target="_blank"
                        class="text-sm font-medium text-bulma-primary hover:text-bulma-primary/80 transition-colors">
                        Blog <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                    </a>
                </div>

                <button id="mobile-menu-btn" type="button"
                    class="md:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700/50">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    {{-- Offcanvas Sidebar para Mobile (Preline UI) --}}
    <div id="sidebar-tools"
        class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full fixed top-0 start-0 transition-all duration-300 transform h-full max-w-xs w-full z-[60] bg-neutral-900 border-e border-neutral-800 hidden lg:hidden"
        tabindex="-1">
        <div class="flex justify-between items-center py-3 px-4 border-b border-neutral-800">
            <h3 class="font-bold text-white">Menu de Ferramentas</h3>
            <button type="button"
                class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-lg text-gray-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all text-sm"
                data-hs-overlay="#sidebar-tools">
                <span class="sr-only">Fechar</span>
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-4 h-[calc(100%-60px)] overflow-y-auto custom-scrollbar">
            @include('layouts.partials.tools-navigation') {{-- Recomendo mover a lista para um partial --}}
        </div>
    </div>

    <div class="flex pt-16 min-h-screen">
        {{-- Sidebar Desktop --}}
        <aside
            class="hidden lg:block w-64 fixed left-0 top-16 h-[calc(100vh-4rem)] bg-neutral-800/50 border-r border-neutral-700/50 overflow-y-auto custom-scrollbar">
            <nav class="p-4">
                @include('layouts.partials.tools-navigation')
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 lg:ml-64 w-full">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-8">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Footer --}}
    <footer class="lg:ml-64 border-t border-neutral-800 py-8 bg-neutral-900/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-gray-500 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} Gabriel Henrique da Silva
            </div>
            <div class="flex items-center gap-6">
                <a href="https://github.com/oGabrielSilva" target="_blank"
                    class="text-gray-500 hover:text-white transition-colors text-xl">
                    <i class="fa-brands fa-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/ogabriel-henrique" target="_blank"
                    class="text-gray-500 hover:text-bulma-link transition-colors text-xl">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            </div>
        </div>
    </footer>
</body>

</html>