<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Portfolio</title>

    @yield('extra_head')
    <meta name="description" content="O laboratório de um desenvolvedor full stack em constante compilação">

    <script src="https://kit.fontawesome.com/78b3364728.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Gabriel" />
    <link rel="manifest" href="/site.webmanifest" />

    @stack('scripts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-gray-300">

    <header>
        <nav class="w-dvw py-6 fixed top-0 z-50 bg-[#1a1a1a]/90 backdrop-blur-md border-b border-white/5">
            <div class="max-w-full mx-auto px-6 flex justify-between items-center">
                <a href="{{ url('/') }}"
                    class="text-xl font-bold text-white tracking-tight hover:text-bulma-primary transition-colors">
                    <img src="/favicon.svg" alt="Logo" class="w-8 h-8 inline-block mr-2">
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ url('/#sobre') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Sobre</a>
                    <a href="{{ url('/#servicos') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Serviços</a>
                    <a href="{{ url('/#blog') }}"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Artigos</a>

                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL()}}" target="_blank"
                        class="text-sm font-medium text-bulma-primary hover:text-bulma-primary/80 transition-colors">
                        Blog <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>

                <button id="mobile-menu-btn" type="button"
                    class="md:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700/50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-all duration-200"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Abrir menu principal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path>
                    </svg>
                </button>
            </div>

            <div id="mobile-menu"
                class="absolute top-full left-0 w-full bg-neutral-800 border-b border-neutral-700 overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0 md:hidden">
                <div class="px-6 py-4 flex flex-col gap-4">
                    <a href="{{ url('/#sobre') }}" class="text-gray-300 hover:text-white transition-colors block">Sobre</a>
                    <a href="{{ url('/#servicos') }}" class="text-gray-300 hover:text-white transition-colors block">Serviços</a>
                    <a href="{{ url('/#blog') }}" class="text-gray-300 hover:text-white transition-colors block">Artigos</a>
                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL()}}" class="text-bulma-primary font-medium block">Ir para o Blog</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-5xl mx-auto px-6 pt-32 pb-32">
        @yield('content')
    </main>

    <footer class="border-t border-neutral-800 py-12 bg-neutral-900">
        <div class="max-w-5xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Gabriel Henrique da Silva
            </div>

            <div class="flex items-center gap-6">
                <a href="https://github.com/oGabrielSilva" target="_blank"
                    class="text-gray-500 hover:text-bulma-primary transition-colors text-xl"><i
                        class="fa-brands fa-github"></i></a>
                <a href="https://www.linkedin.com/in/ogabriel-henrique" target="_blank"
                    class="text-gray-500 hover:text-bulma-link transition-colors text-xl"><i
                        class="fa-brands fa-linkedin"></i></a>

            </div>
        </div>
    </footer>
</body>

</html>
