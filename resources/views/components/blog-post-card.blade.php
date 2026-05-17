@props(['post'])

@php
    $featuredImg = $post->getFirstMediaUrl('cover') ?: 'https://placehold.co/600x400/242424/333?text=Artigo';
    $date = $post->published_at?->translatedFormat('d \d\e F \d\e Y');
    $excerpt = $post->excerpt ?: Str::limit(strip_tags($post->body_html ?? ''), 100);
    $link = route('blog.show', $post);
@endphp

<article
    class="bg-neutral-800 rounded-lg overflow-hidden border border-neutral-700 hover:border-bulma-primary transition-colors flex flex-col group"
    data-aos="fade-up">
    <a href="{{ $link }}" class="block overflow-hidden h-40">
        <img src="{{ $featuredImg }}" alt="{{ $post->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    </a>
    <div class="p-5 flex-1 flex flex-col">
        @if($date)
            <div class="text-xs text-bulma-primary mb-2">
                <time datetime="{{ $post->published_at->toAtomString() }}">{{ $date }}</time>
            </div>
        @endif
        <h3 class="text-lg font-bold text-white mb-2 leading-tight">
            <a href="{{ $link }}" class="hover:text-bulma-primary transition-colors">
                {{ $post->title }}
            </a>
        </h3>
        <div class="text-gray-400 text-sm line-clamp-2 mb-4 flex-1">
            {{ $excerpt }}
        </div>
        <a href="{{ $link }}"
            class="text-sm font-medium text-white hover:text-bulma-primary transition-colors inline-flex items-center mt-auto">
            Ler artigo <i
                class="fa-solid fa-arrow-right text-xs ml-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </a>
    </div>
</article>
