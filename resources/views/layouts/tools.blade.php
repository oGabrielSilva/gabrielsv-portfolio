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

<body class="antialiased text-gray-300 max-w-dvw overflow-x-hidden w-dvw">
    {{-- Header --}}
    <header>
        <nav class="w-dvw py-4 fixed top-0 z-50 bg-[#1a1a1a]/90 backdrop-blur-md border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}"
                        class="text-xl font-bold text-white tracking-tight hover:text-bulma-primary transition-colors">
                        <img src="/favicon.svg" alt="Logo" class="w-8 h-8 inline-block">
                    </a>
                    <span class="text-gray-600">/</span>
                    <a href="{{ route('tools.index') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        Ferramentas
                    </a>
                    @hasSection('tool_name')
                        <span class="text-gray-600">/</span>
                        <span class="text-sm font-medium text-white">@yield('tool_name')</span>
                    @endif
                </div>

                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-home mr-1"></i> Início
                    </a>
                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL() }}" target="_blank"
                        class="text-sm font-medium text-bulma-primary hover:text-bulma-primary/80 transition-colors">
                        Blog <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" type="button"
                    class="md:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700/50"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div id="mobile-menu"
                class="absolute top-full left-0 w-full bg-neutral-800 border-b border-neutral-700 overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0 md:hidden">
                <div class="px-6 py-4 flex flex-col gap-4">
                    <a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition-colors block">Início</a>
                    <a href="{{ route('tools.index') }}"
                        class="text-gray-300 hover:text-white transition-colors block">Ferramentas</a>
                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL() }}"
                        class="text-bulma-primary font-medium block">Ir para o Blog</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="flex pt-16 min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="hidden lg:block w-64 fixed left-0 top-16 h-[calc(100vh-4rem)] bg-neutral-800/50 border-r border-neutral-700/50 overflow-y-auto">
            <nav class="p-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Ferramentas</h3>
                <ul class="space-y-1">
                    @foreach($toolsList as $tool)
                        <li>
                            <a href="{{ route($tool['route']) }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                                                                  {{ request()->routeIs($tool['routeMatch']) ? 'bg-bulma-primary/10 text-bulma-primary' : 'text-gray-400 hover:text-white hover:bg-neutral-700/50' }}">
                                <i class="fa-solid {{ $tool['icon'] }} w-4"></i>
                                {{ $tool['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 mt-6">Em breve</h3>
                <ul class="space-y-1">
                    @php
                        $upcoming = [
                            ['icon' => 'fa-code', 'name' => 'JSON Formatter'],
                            ['icon' => 'fa-lock', 'name' => 'Base64 Encode/Decode'],
                            ['icon' => 'fa-palette', 'name' => 'Color Converter'],
                        ];
                    @endphp
                    @foreach($upcoming as $tool)
                        <li>
                            <span
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 cursor-not-allowed">
                                <i class="fa-solid {{ $tool['icon'] }} w-4"></i>
                                {{ $tool['name'] }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 lg:ml-64">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Footer --}}
    <footer class="lg:ml-64 border-t border-neutral-800 py-8 bg-neutral-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Gabriel Henrique da Silva
            </div>
            <div class="flex items-center gap-6">
                <a href="https://github.com/oGabrielSilva" target="_blank"
                    class="text-gray-500 hover:text-bulma-primary transition-colors text-xl">
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