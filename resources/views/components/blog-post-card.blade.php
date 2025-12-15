@props(['post'])

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
