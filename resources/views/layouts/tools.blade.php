<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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
    <header class="fixed top-0 z-50 w-full bg-[#1a1a1a]/95 backdrop-blur-md border-b border-white/5">
        <nav class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">

            {{-- Lado Esquerdo: Botão Sidebar + Logo + Breadcrumbs --}}
            <div class="flex items-center gap-3 min-w-0">
                {{-- Botão Sidebar Mobile (Gatilho Preline) --}}
                <button type="button"
                    class="lg:hidden p-2 inline-flex items-center justify-center rounded-lg border border-neutral-700 bg-neutral-800 text-gray-400 hover:text-white transition-all"
                    data-hs-overlay="#sidebar-tools">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>

                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="/favicon.svg" alt="Logo" class="w-7 h-7 md:w-8 md:h-8">
                </a>

                <div class="hidden xs:flex items-center gap-2 text-sm ml-2 overflow-hidden">
                    <span class="text-gray-700">/</span>
                    <a href="{{ route('tools.index') }}"
                        class="text-gray-400 hover:text-white transition-colors">Ferramentas</a>
                    @hasSection('tool_name')
                        <span class="text-gray-700">/</span>
                        <span class="text-white font-medium truncate">@yield('tool_name')</span>
                    @endif
                </div>
            </div>

            {{-- Lado Direito: Links Globais --}}
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Início</a>
                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL() }}" target="_blank"
                        class="text-sm font-medium text-bulma-primary hover:text-white transition-colors flex items-center gap-1">
                        Blog <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>

                {{-- Menu de Navegação do Site (Início/Blog) no Mobile --}}
                <button id="mobile-menu-btn" type="button"
                    class="md:hidden p-2 rounded-lg text-gray-400 hover:bg-white/5 transition-all">
                    <i class="fa-solid fa-ellipsis-vertical text-xl"></i>
                </button>
            </div>
        </nav>

        {{-- Mobile Site Menu (Início, Blog, etc) --}}
        <div id="mobile-menu"
            class="absolute top-full left-0 w-full bg-[#1a1a1a] border-b border-neutral-800 overflow-hidden transition-all duration-300 max-h-0 opacity-0 md:hidden">
            <div class="px-6 py-6 flex flex-col gap-4">
                <a href="{{ url('/') }}" class="text-white font-medium flex items-center gap-3"><i
                        class="fa-solid fa-house w-5 text-gray-500"></i> Início</a>
                <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL() }}"
                    class="text-bulma-primary font-medium flex items-center gap-3"><i class="fa-solid fa-rss w-5"></i>
                    Ir para o Blog</a>
            </div>
        </div>
    </header>

    {{-- Offcanvas Sidebar Mobile (Ferramentas) --}}
    <div id="sidebar-tools"
        class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full fixed top-0 start-0 transition-all duration-300 transform h-full max-w-xs w-full z-[60] bg-neutral-900 border-e border-neutral-800 hidden lg:hidden"
        tabindex="-1">
        <div class="flex justify-between items-center py-4 px-6 border-b border-neutral-800">
            <h3 class="font-bold text-white tracking-tight">Ferramentas</h3>
            <button type="button" class="text-gray-500 hover:text-white p-2" data-hs-overlay="#sidebar-tools">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-4 h-[calc(100%-70px)] overflow-y-auto">
            <ul class="space-y-1">
                @foreach($toolsList as $tool)
                    <li>
                        <a href="{{ route($tool['route']) }}"
                            class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm transition-all
                                {{ request()->routeIs($tool['routeMatch']) ? 'bg-bulma-primary text-neutral-900 font-bold' : 'text-gray-400' }}">
                            <i class="fa-solid {{ $tool['icon'] }} w-5 text-center"></i>
                            {{ $tool['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="flex pt-16 min-h-screen">
        {{-- Sidebar Desktop --}}
        <aside
            class="hidden lg:block w-64 fixed left-0 top-16 h-[calc(100vh-4rem)] bg-[#161616]/50 border-r border-white/5 overflow-y-auto custom-scrollbar">
            <nav class="p-4">
                <h3 class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.2em] mb-4 px-3">Navegação</h3>
                <ul class="space-y-1">
                    @foreach($toolsList as $tool)
                        <li>
                            <a href="{{ route($tool['route']) }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all
                                    {{ request()->routeIs($tool['routeMatch']) ? 'bg-bulma-primary/10 text-bulma-primary font-semibold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                <i class="fa-solid {{ $tool['icon'] }} w-4 text-center"></i>
                                {{ $tool['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 lg:ml-64 w-full">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Footer --}}
    <footer class="w-full lg:pl-64 border-t border-white/5 bg-[#1a1a1a]/30 py-10 mt-auto">
        <div class="max-w-4xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-gray-500 text-xs md:text-sm text-center md:text-left leading-relaxed">
                &copy; {{ date('Y') }} Gabriel Henrique da Silva <br class="md:hidden">
                <span class="hidden md:inline text-gray-800 mx-2">|</span>
                Online & Local Processing
            </div>

            <div class="flex items-center gap-6">
                <a href="https://github.com/oGabrielSilva" target="_blank"
                    class="text-gray-500 hover:text-white transition-colors">
                    <i class="fa-brands fa-github text-2xl"></i>
                </a>
                <a href="https://www.linkedin.com/in/ogabriel-henrique" target="_blank"
                    class="text-gray-500 hover:text-white transition-colors">
                    <i class="fa-brands fa-linkedin text-2xl"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Menu de Navegação Geral (Mobile)
        const menuBtn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        menuBtn?.addEventListener('click', () => {
            const isOpen = menu.style.maxHeight !== '0px' && menu.style.maxHeight !== '';
            menu.style.maxHeight = isOpen ? '0px' : '200px';
            menu.style.opacity = isOpen ? '0' : '1';
        });
    </script>
</body>

</html>