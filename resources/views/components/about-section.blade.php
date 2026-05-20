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

            <a href="{{ route('about') }}"
                class="mt-8 inline-flex items-center gap-1.5 text-sm font-medium text-bulma-primary transition-colors hover:text-bulma-primary/80">
                <span>Página completa</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="relative flex justify-center items-center" data-aos="fade-left">
            <div class="absolute w-64 h-64 md:w-80 md:h-80 border border-neutral-700/50 -z-10 animate-[spin_10s_linear_infinite]"
                style="border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;"></div>

            <div class="w-64 h-64 md:w-80 md:h-80 overflow-hidden relative z-10 bg-neutral-800 transition-all duration-500 ease-in-out hover:scale-105"
                style="border-radius: 56% 44% 71% 29% / 46% 56% 44% 54%; box-shadow: 0 0 0 8px rgba(26, 26, 26, 0.5);">
                <picture>
                    <source
                        type="image/webp"
                        srcset="/img/hero-512.webp 512w, /img/hero-896.webp 896w, /img/hero-1024.webp 1024w"
                        sizes="(min-width: 768px) 320px, 256px">
                    <img src="/img/hero-512.jpg"
                        srcset="/img/hero-512.jpg 512w, /img/hero-896.jpg 896w, /img/hero-1024.jpg 1024w"
                        sizes="(min-width: 768px) 320px, 256px"
                        alt="Imagem de Gabriel Silva"
                        width="512" height="683"
                        loading="lazy" decoding="async"
                        class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-400">
                </picture>
            </div>
        </div>
    </div>
</section>
