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
                <a href="#blog-section"
                    class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Artigos</a>
                <a href="#work"
                    class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Projetos</a>
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
                <a href="#work" class="text-gray-300">Trabalho</a>
                <a href="#blog-section" class="text-gray-300">Artigos</a>
                <a href="https://eu.gabrielsv.com" class="text-bulma-primary font-medium">Ir para o Blog</a>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 pt-32 pb-32">

        <div class="max-w-2xl py-10" data-aos="fade-up" data-aos-delay="200">
            <span class="text-bulma-primary font-medium tracking-wide text-sm mb-4 block uppercase">DESENVOLVEDOR
                FULLSTACK</span>
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 tracking-tight leading-tight">
                Construindo experiências digitais sólidas e funcionais.
            </h1>
            <p class="text-xl text-gray-400 mb-10 leading-relaxed">
                Olá, eu sou Gabriel. Escrevo código e compartilho conhecimento.
                O foco central do meu conteúdo está no meu subdomínio pessoal.
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
                    <h2 class="text-3xl font-bold text-white mb-6">Gabriel SV</h2>
                    <div class="space-y-4 text-gray-400 leading-relaxed text-lg">
                        <p>
                            Sou um desenvolvedor apaixonado por transformar ideias complexas em interfaces simples e
                            intuitivas. Minha jornada na tecnologia começou pela curiosidade de entender como as coisas
                            funcionam por trás da tela.
                        </p>
                        <p>
                            Atualmente, foco em construir soluções robustas utilizando tecnologias modernas como React e
                            Node.js. Acredito no poder do código limpo e na importância de compartilhar conhecimento com
                            a comunidade.
                        </p>
                        <p>
                            Quando não estou programando, você pode me encontrar escrevendo novos artigos para o blog ou
                            explorando novas tecnologias.
                        </p>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold text-white">5+</span>
                            <span class="text-sm text-gray-500">Anos de XP</span>
                        </div>
                        <!-- <div class="w-px bg-neutral-700 mx-2"></div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold text-white">20+</span>
                            <span class="text-sm text-gray-500">Projetos</span>
                        </div> -->
                    </div>
                </div>

                <div class="relative flex justify-center items-center" data-aos="fade-left">
                    <div class="absolute w-64 h-64 md:w-80 md:h-80 border border-neutral-700/50 -z-10 animate-[spin_10s_linear_infinite]"
                        style="border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;"></div>

                    <div class="w-64 h-64 md:w-80 md:h-80 overflow-hidden relative z-10 bg-neutral-800 transition-all duration-500 ease-in-out hover:scale-105"
                        style="border-radius: 56% 44% 71% 29% / 46% 56% 44% 54%; box-shadow: 0 0 0 8px rgba(26, 26, 26, 0.5);">
                        <img src="/IMG_20241226_184124563.jpg" alt="Imagem de Gabriel Silva"
                            class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-700">
                    </div>
                </div>
            </div>
        </section>

        <section id="servicos" class="mb-24 scroll-mt-32">
            <div class="flex items-end justify-between mb-10" data-aos="fade-right">
                <h2 class="text-3xl font-bold text-white">Meus Serviços</h2>
                <div class="h-1 w-20 bg-bulma-primary hidden md:block rounded"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-8 bg-neutral-800 rounded-lg border border-neutral-700 hover:border-bulma-primary transition-all duration-300 hover:-translate-y-2 group"
                    data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="w-12 h-12 bg-neutral-900 rounded-lg flex items-center justify-center mb-6 text-bulma-primary text-2xl group-hover:bg-bulma-primary group-hover:text-neutral-900 transition-colors">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Desenvolvimento Web</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Criação de sites e aplicações web modernas
                        utilizando React, Node.js e tecnologias de ponta.</p>
                </div>

                <div class="p-8 bg-neutral-800 rounded-lg border border-neutral-700 hover:border-bulma-link transition-all duration-300 hover:-translate-y-2 group"
                    data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="w-12 h-12 bg-neutral-900 rounded-lg flex items-center justify-center mb-6 text-bulma-link text-2xl group-hover:bg-bulma-link group-hover:text-white transition-colors">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Backend & API</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Arquitetura de servidores, bancos de dados
                        SQL/NoSQL e construção de APIs RESTful escaláveis.</p>
                </div>

                <div class="p-8 bg-neutral-800 rounded-lg border border-neutral-700 hover:border-purple-500 transition-all duration-300 hover:-translate-y-2 group"
                    data-aos="fade-up" data-aos-delay="300">
                    <div
                        class="w-12 h-12 bg-neutral-900 rounded-lg flex items-center justify-center mb-6 text-purple-500 text-2xl group-hover:bg-purple-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Otimização</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Melhoria de performance, SEO técnico e refatoração
                        de código para garantir velocidade.</p>
                </div>
            </div>
        </section>

        <section id="blog-section" class="mb-24 py-10 border-t border-b border-neutral-800 scroll-mt-32">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">

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
        </div>

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
