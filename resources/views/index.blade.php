<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabriel Henrique da Silva - Desenvolvedor Fullstack</title>
    <meta name="description" content="O laboratório de um desenvolvedor full stack em constante compilação">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6896632008434347"
        crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-gray-300">

    <nav class="w-full py-6 fixed top-0 z-50 bg-[#1a1a1a]/90 backdrop-blur-md border-b border-white/5"
        data-aos="fade-down" data-aos-duration="800">
        <div class="max-w-5xl mx-auto px-6 flex justify-between items-center">
            <a href="{{ url('/') }}"
                class="text-xl font-bold text-white tracking-tight hover:text-bulma-primary transition-colors">
                <img src="/favicon.svg" alt="Logo" class="w-8 h-8 inline-block mr-2">
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="#sobre" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Sobre</a>
                <a href="#servicos"
                    class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Serviços</a>
                <a href="#blog" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Artigos</a>

                <a href="https://eu.gabrielsv.com" target="_blank"
                    class="text-sm font-medium text-bulma-primary hover:text-bulma-primary/80 transition-colors">
                    Blog <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <button id="mobile-menu-btn" class="md:hidden text-gray-300">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

        <div id="mobile-menu"
            class="hidden px-6 py-4 bg-neutral-800 border-t border-neutral-700 md:hidden absolute w-full">
            <div class="flex flex-col gap-4">
                <a href="#sobre" class="text-gray-300">Sobre</a>
                <a href="#servicos" class="text-gray-300">Serviços</a>
                <a href="#blog" class="text-gray-300">Artigos</a>
                <a href="https://eu.gabrielsv.com" class="text-bulma-primary font-medium">Ir para o Blog</a>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 pt-32 pb-32">

        <div class="py-12" data-aos="fade-up" data-aos-delay="200">
            <span class="text-bulma-primary font-medium tracking-wide text-sm mb-4 block uppercase">DESENVOLVEDOR
                FULLSTACK</span>
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-0 tracking-tight leading-tight">
                Gabriel Henrique da Silva
            </h1>
            <h3 class="mb-6 text-5xl md:text-6xl font-bold text-gray-400 tracking-tight leading-tight">
                Código limpo. Soluções robustas.
            </h3>
            <p class="max-w-xl text-gray-400 pb-10 leading-relaxed">
                Especialista em Desenvolvimento Web com foco em ecossistemas modernos do Node.js e PHP. Uso este espaço
                para unir o útil ao agradável: compartilhar insights sobre tecnologia e carreira enquanto aprimoro, em
                público, o Capsulepress, o tema que dá vida a estas páginas.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="https://eu.gabrielsv.com" target="_blank"
                    class="bg-bulma-primary hover:bg-opacity-90 text-neutral-900 font-bold py-3 px-6 rounded transition-all inline-flex items-center gap-2 transform hover:-translate-y-1">
                    Acessar meus Artigos
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
                <a href="mailto:help@rota42.com"
                    class="border border-neutral-700 hover:border-gray-500 text-white font-medium py-3 px-6 rounded transition-all bg-neutral-800 transform hover:-translate-y-1">
                    Entrar em contato
                </a>
            </div>
        </div>

        <hr class="border-neutral-800 my-20" data-aos="fade-in">

        <section id="sobre" class="mb-24 scroll-mt-32">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-px w-8 bg-bulma-primary"></div>
                        <span class="text-bulma-primary font-medium text-sm tracking-wider uppercase">Quem sou eu</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-6">Gabriel Silva</h2>
                    <div class="space-y-4 text-gray-400 leading-relaxed text-lg">
                        <p>
                            Com pós-graduação em Desenvolvimento Web, minha abordagem vai além da sintaxe.
                            Foco na arquitetura completa da aplicação, unindo performance no backend com interfaces
                            reativas no frontend.
                        </p>
                        <p>
                            Meu stack principal gira em torno de Laravel, Node.js e Vue/Nuxt, mas também tenho um pé
                            firme na infraestrutura.
                        </p>
                        <p>
                            Quando não estou desenvolvendo ou refatorando código, estou documentando esses
                            aprendizados no meu blog ou explorando novas tecnologias do ecossistema web.
                        </p>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold text-white">5+</span>
                            <span class="text-sm text-gray-500">Anos de XP</span>
                        </div>
                        <div class="w-px bg-neutral-700 mx-2"></div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold text-white">15+</span>
                            <span class="text-sm text-gray-500">Artigos</span>
                        </div>
                    </div>
                </div>

                <div class="relative flex justify-center items-center" data-aos="fade-left">
                    <div class="absolute w-64 h-64 md:w-80 md:h-80 border border-neutral-700/50 -z-10 animate-[spin_10s_linear_infinite]"
                        style="border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;"></div>

                    <div class="w-64 h-64 md:w-80 md:h-80 overflow-hidden relative z-10 bg-neutral-800 transition-all duration-500 ease-in-out hover:scale-105"
                        style="border-radius: 56% 44% 71% 29% / 46% 56% 44% 54%; box-shadow: 0 0 0 8px rgba(26, 26, 26, 0.5);">
                        <img src="/IMG_20241226_184124563.jpg" alt="Imagem de Gabriel Silva"
                            class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-400">
                    </div>
                </div>
            </div>
        </section>

        <section id="servicos" class="mb-24 scroll-mt-32">
            <div class="flex items-end justify-between mb-12" data-aos="fade-right">
                <div>
                    <span class="text-bulma-primary font-bold tracking-wider text-sm uppercase">O que eu faço</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">Soluções Especializadas</h2>
                </div>
                <div class="h-1 w-24 bg-bulma-primary hidden md:block rounded"></div>
            </div>

            <div class="flex flex-wrap justify-center gap-6">

                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(33.33%-16px)] p-8 bg-neutral-800/50 backdrop-blur-sm rounded-xl border border-neutral-700
                    hover:border-bulma-primary hover:bg-neutral-800 hover:shadow-[0_10px_40px_-10px_rgba(0,209,178,0.2)]
                    transition-all duration-500 ease-out hover:-translate-y-2 group" data-aos="fade-up"
                    data-aos-delay="0">

                    <div class="w-12 h-12 bg-neutral-900/80 rounded-lg flex items-center justify-center mb-6 text-bulma-primary text-2xl
                        group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 ease-out">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-white mb-3 group-hover:text-bulma-primary transition-colors duration-300">
                        Frontend Moderno</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Desenvolvimento de interfaces reativas e SPAs utilizando <strong>Vue.js</strong> e
                        <strong>Nuxt</strong>. Foco total em usabilidade e design systems consistentes.
                    </p>
                </div>

                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(33.33%-16px)] p-8 bg-neutral-800/50 backdrop-blur-sm rounded-xl border border-neutral-700
                    hover:border-blue-500 hover:bg-neutral-800 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.2)]
                    transition-all duration-500 ease-out hover:-translate-y-2 group" data-aos="fade-up"
                    data-aos-delay="100">

                    <div class="w-12 h-12 bg-neutral-900/80 rounded-lg flex items-center justify-center mb-6 text-blue-500 text-2xl
                        group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 ease-out">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-white mb-3 group-hover:text-blue-500 transition-colors duration-300">
                        Backend & API</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Arquitetura sólida com <strong>Laravel</strong> ou <strong>Node.js</strong>. Criação de APIs
                        RESTful seguras, integrações complexas e modelagem de bancos de dados.
                    </p>
                </div>

                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(33.33%-16px)] p-8 bg-neutral-800/50 backdrop-blur-sm rounded-xl border border-neutral-700
                    hover:border-sky-400 hover:bg-neutral-800 hover:shadow-[0_10px_40px_-10px_rgba(56,189,248,0.2)]
                    transition-all duration-500 ease-out hover:-translate-y-2 group" data-aos="fade-up"
                    data-aos-delay="200">

                    <div class="w-12 h-12 bg-neutral-900/80 rounded-lg flex items-center justify-center mb-6 text-sky-400 text-2xl
                        group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 ease-out">
                        <i class="fa-brands fa-wordpress"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-white mb-3 group-hover:text-sky-400 transition-colors duration-300">
                        Soluções WordPress</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Desenvolvimento de <strong>temas personalizados</strong> e plugins. Transformo o CMS mais usado
                        do mundo em plataformas robustas e performáticas.
                    </p>
                </div>

                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(33.33%-16px)] p-8 bg-neutral-800/50 backdrop-blur-sm rounded-xl border border-neutral-700
                    hover:border-orange-400 hover:bg-neutral-800 hover:shadow-[0_10px_40px_-10px_rgba(251,146,60,0.2)]
                    transition-all duration-500 ease-out hover:-translate-y-2 group" data-aos="fade-up"
                    data-aos-delay="300">

                    <div class="w-12 h-12 bg-neutral-900/80 rounded-lg flex items-center justify-center mb-6 text-orange-400 text-2xl
                        group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 ease-out">
                        <i class="fa-regular fa-envelope-open"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-white mb-3 group-hover:text-orange-400 transition-colors duration-300">
                        Email Development</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Desenvolvimento de e-mails compatíveis com os principais clientes de e-mail.
                    </p>
                </div>

                <div class="w-full md:w-[calc(50%-12px)] lg:w-[calc(33.33%-16px)] p-8 bg-neutral-800/50 backdrop-blur-sm rounded-xl border border-neutral-700
                    hover:border-emerald-400 hover:bg-neutral-800 hover:shadow-[0_10px_40px_-10px_rgba(52,211,153,0.2)]
                    transition-all duration-500 ease-out hover:-translate-y-2 group" data-aos="fade-up"
                    data-aos-delay="500">

                    <div class="w-12 h-12 bg-neutral-900/80 rounded-lg flex items-center justify-center mb-6 text-emerald-400 text-2xl
                        group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 ease-out">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-white mb-3 group-hover:text-emerald-400 transition-colors duration-300">
                        Performance & SEO</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Otimização técnica (Core Web Vitals), melhoria de tempo de carregamento e boas práticas de SEO
                        aplicadas diretamente no código.
                    </p>
                </div>

            </div>
        </section>

        <section id="blog" class="mb-24 py-10 border-t border-b border-neutral-800 scroll-mt-32">
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Últimos Artigos</h2>
                    <p class="text-gray-400 text-sm">Direto de <span class="text-bulma-primary">eu.gabrielsv.com</span>
                    </p>
                </div>
                <a href="https://eu.gabrielsv.com" target="_blank"
                    class="text-sm font-bold text-bulma-primary hover:text-white transition-colors">
                    Ver todos os posts <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($posts as $post)
                    @php
                        $featuredImg = $post['_embedded']['wp:featuredmedia'][0]['source_url']
                            ?? 'https://placehold.co/600x400/242424/333?text=Artigo';
                        $date = \Carbon\Carbon::parse($post['date'])->translatedFormat('d \d\e F \d\e Y');
                        $excerpt = Str::limit(strip_tags($post['excerpt']['rendered']), 100);
                        $title = $post['title']['rendered'];
                        $link = $post['link'];
                    @endphp

                    <article
                        class="bg-neutral-800 rounded-lg overflow-hidden border border-neutral-700 hover:border-bulma-primary transition-colors flex flex-col group"
                        data-aos="fade-up">
                        <a href="{{ $link }}" target="_blank" class="block overflow-hidden h-40">
                            <img src="{{ $featuredImg }}" alt="{{ $title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="text-xs text-bulma-primary mb-2">
                                <time datetime="{{ $post['date'] }}">{{ $date }}</time>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2 leading-tight">
                                <a href="{{ $link }}" target="_blank" class="hover:text-bulma-primary transition-colors">
                                    {!! $title !!}
                                </a>
                            </h3>
                            <div class="text-gray-400 text-sm line-clamp-2 mb-4 flex-1">
                                {{ $excerpt }}
                            </div>
                            <a href="{{ $link }}" target="_blank"
                                class="text-sm font-medium text-white hover:text-bulma-primary transition-colors inline-flex items-center mt-auto">
                                Ler artigo <i
                                    class="fa-solid fa-arrow-right text-xs ml-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-400 mb-4">Acesse o blog para conferir o conteúdo completo.</p>
                        <a href="https://eu.gabrielsv.com" target="_blank"
                            class="inline-block px-6 py-2 border border-bulma-primary text-bulma-primary rounded hover:bg-bulma-primary hover:text-neutral-900 transition-colors">
                            Acessar Blog
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-16">

            <section id="work" data-aos="fade-right" class="scroll-mt-32">
                <h2 class="text-2xl font-bold text-white mb-6">Projetos Selecionados</h2>
                <div class="space-y-6">
                    <a href="#" class="block group">
                        <div
                            class="p-5 bg-neutral-800 rounded border border-neutral-700 hover:border-bulma-link transition-colors duration-300">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-white group-hover:text-bulma-link transition-colors">
                                    Painel Administrativo</h3>
                                <i class="fa-brands fa-github text-gray-500 group-hover:text-white"></i>
                            </div>
                            <p class="text-gray-400 text-sm mb-3">Dashboard responsivo utilizando React e Bulma CSS para
                                visualização de dados.</p>
                            <div class="text-xs text-bulma-primary">Ver projeto →</div>
                        </div>
                    </a>
                    <a href="#" class="block group">
                        <div
                            class="p-5 bg-neutral-800 rounded border border-neutral-700 hover:border-bulma-link transition-colors duration-300">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-white group-hover:text-bulma-link transition-colors">
                                    API RESTful</h3>
                                <i class="fa-solid fa-server text-gray-500 group-hover:text-white"></i>
                            </div>
                            <p class="text-gray-400 text-sm mb-3">Backend robusto em Node.js com arquitetura limpa e
                                testes automatizados.</p>
                        </div>
                    </a>
                </div>
            </section>

            <section id="stack" data-aos="fade-left" class="scroll-mt-32">
                <h2 class="text-2xl font-bold text-white mb-6">Tech Stack</h2>
                <div class="space-y-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Frontend</h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-primary transition-colors cursor-default">React</span>
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-primary transition-colors cursor-default">Vue</span>
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-primary transition-colors cursor-default">Angular</span>
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-primary transition-colors cursor-default">TypeScript</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Backend & DevOps
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-link transition-colors cursor-default">Node.js</span>
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-link transition-colors cursor-default">Laravel</span>
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-link transition-colors cursor-default">WordPress</span>
                            <span
                                class="px-3 py-1 bg-neutral-800 border border-neutral-700 rounded text-sm text-gray-300 hover:text-bulma-link transition-colors cursor-default">Nest.js</span>
                        </div>
                    </div>
                </div>
            </section>
        </div> -->

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
