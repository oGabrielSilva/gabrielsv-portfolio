@extends('layouts.app')

@push('scripts')
    @if (config('services.google_ads.client_id'))
        <script async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.google_ads.client_id') }}"
            crossorigin="anonymous"></script>
    @endif
@endpush

@section('content')
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
            <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL()}}" target="_blank"
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

    <x-about-section />

    <section id="servicos" class="mb-24 scroll-mt-32">
        <div class="flex items-end justify-between mb-12" data-aos="fade-right">
            <div>
                <span class="text-bulma-primary font-bold tracking-wider text-sm uppercase">O que eu faço</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">Soluções Especializadas</h2>
            </div>
            <div class="h-1 w-24 bg-bulma-primary hidden md:block rounded"></div>
        </div>

        <div class="flex flex-wrap justify-center gap-6">
            @foreach ($services as $service)
                <x-service-card :icon="$service['icon']" :title="$service['title']" :description="$service['description']"
                    :color="$service['color']" :delay="$service['delay']" />
            @endforeach
        </div>
    </section>

    <section id="blog" class="mb-24 py-10 border-t border-neutral-800 scroll-mt-32">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4" data-aos="fade-up">
            <div>
                <h2 class="text-3xl font-bold text-white mb-2">Últimos Artigos</h2>
                <p class="text-gray-400 text-sm">Direto de <span class="text-bulma-primary">eu.gabrielsv.com</span>
                </p>
            </div>
            <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL()}}" target="_blank"
                class="text-sm font-bold text-bulma-primary hover:text-white transition-colors">
                Ver todos os posts <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($posts as $post)
                <x-blog-post-card :post="$post" />
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-400 mb-4">Acesse o blog para conferir o conteúdo completo.</p>
                    <a href="{{ \App\Utils\BlogHelper::getOwnerBlogURL()}}" target="_blank"
                        class="inline-block px-6 py-2 border border-bulma-primary text-bulma-primary rounded hover:bg-bulma-primary hover:text-neutral-900 transition-colors">
                        Acessar Blog
                    </a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
