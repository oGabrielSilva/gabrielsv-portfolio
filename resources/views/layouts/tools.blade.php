<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <script src="https://unpkg.com/lucide@latest"></script>
    @include('partials.head')
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
                    <a href="{{ route('tools.index') }}"
                        class="hidden sm:inline text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        Ferramentas
                    </a>
                    @hasSection('tool_name')
                        <span class="text-gray-600 hidden sm:inline">/</span>
                        <span class="text-sm font-medium text-white truncate hidden sm:inline">@yield('tool_name')</span>
                    @endif
                </div>

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-4 lg:gap-6">
                    <a href="{{ url('/') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1.5">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        <span>Início</span>
                    </a>
                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL() }}" target="_blank"
                        class="text-sm font-medium text-bulma-primary hover:text-bulma-primary/80 transition-colors inline-flex items-center gap-1.5">
                        <span>Blog</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
                class="absolute top-full left-0 w-full bg-neutral-800 border-b border-neutral-700 overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0 md:hidden">
                <div class="px-4 py-4 space-y-4">
                    {{-- Navigation links --}}
                    <div class="flex flex-col gap-2">
                        <a href="{{ url('/') }}"
                            class="text-gray-300 hover:text-white transition-colors py-2 inline-flex items-center gap-2">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            Início
                        </a>
                        <a href="{{ route('tools.index') }}"
                            class="text-gray-300 hover:text-white transition-colors py-2 inline-flex items-center gap-2">
                            <i data-lucide="wrench" class="w-4 h-4"></i>
                            Todas as Ferramentas
                        </a>
                    </div>

                    {{-- Tools list --}}
                    <div class="border-t border-neutral-700 pt-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Ferramentas</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($toolsList as $tool)
                                <a href="{{ route($tool['route']) }}"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                        {{ request()->routeIs($tool['routeMatch']) ? 'bg-bulma-primary/10 text-bulma-primary' : 'text-gray-400 hover:text-white hover:bg-neutral-700/50' }}">
                                    <i data-lucide="{{ $tool['icon'] }}" class="w-4 h-4 shrink-0"></i>
                                    <span class="truncate">{{ $tool['name'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Blog link --}}
                    <div class="border-t border-neutral-700 pt-4">
                        <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL() }}"
                            class="text-bulma-primary font-medium py-2 inline-flex items-center gap-2">
                            <span>Ir para o Blog</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="flex pt-14 sm:pt-16 min-h-screen">
        {{-- Sidebar (desktop only) --}}
        <aside
            class="hidden lg:block w-64 fixed left-0 top-14 sm:top-16 h-[calc(100vh-3.5rem)] sm:h-[calc(100vh-4rem)] bg-neutral-800/50 border-r border-neutral-700/50 overflow-y-auto">
            <nav class="p-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Ferramentas</h3>
                <ul class="space-y-1">
                    @foreach($toolsList as $tool)
                        <li>
                            <a href="{{ route($tool['route']) }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                                    {{ request()->routeIs($tool['routeMatch']) ? 'bg-bulma-primary/10 text-bulma-primary' : 'text-gray-400 hover:text-white hover:bg-neutral-700/50' }}">
                                <i data-lucide="{{ $tool['icon'] }}" class="w-4 h-4"></i>
                                {{ $tool['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 mt-6">Em breve</h3>
                <ul class="space-y-1">
                    @php
                        $upcoming = [
                            ['icon' => 'code', 'name' => 'JSON Formatter'],
                            ['icon' => 'lock', 'name' => 'Base64 Encode/Decode'],
                            ['icon' => 'palette', 'name' => 'Color Converter'],
                        ];
                    @endphp
                    @foreach($upcoming as $tool)
                        <li>
                            <span class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 cursor-not-allowed">
                                <i data-lucide="{{ $tool['icon'] }}" class="w-4 h-4"></i>
                                {{ $tool['name'] }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 lg:ml-64">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                @yield('content')
            </div>
        </main>
    </div>

    @include('partials.footer', ['footerClass' => 'lg:ml-64'])

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>

</html>
