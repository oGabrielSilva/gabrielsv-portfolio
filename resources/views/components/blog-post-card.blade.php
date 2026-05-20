@props(['post'])

@php
    $coverMedia = $post->getFirstMedia('cover');
    $hasCover = (bool) $coverMedia;
    $cover = $coverMedia?->getUrl();
    $coverAlt = $coverMedia?->getCustomProperty('alt') ?: $post->title;
    $date = $post->published_at;
    $excerpt = $post->excerpt ?: Str::limit(strip_tags($post->body_html ?? ''), 120);
    $link = route('blog.show', $post);
    $primaryCategory = $post->categories->first();
    $readingTime = $post->reading_time ?? 1;
@endphp

<article
    class="spotlight group flex flex-col overflow-hidden rounded-2xl border border-neutral-800 bg-neutral-900/40 transition-colors hover:cursor-pointer hover:border-bulma-primary/40 focus-within:border-bulma-primary/60"
    data-card-link="{{ $link }}"
    data-aos="fade-up"
>
    <div class="block aspect-video overflow-hidden bg-neutral-800">
        @if($cover)
            <img
                src="{{ $cover }}"
                alt="{{ $coverAlt }}"
                class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
                decoding="async"
            >
        @else
            <div class="flex size-full items-center justify-center bg-linear-to-br from-neutral-800 to-neutral-900">
                <span class="font-mono text-xs text-neutral-700">{{ $post->slug }}</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-3 p-5">
        @if($primaryCategory)
            <div>
                <x-blog.chip
                    :label="$primaryCategory->name"
                    :slug="$primaryCategory->slug"
                    :href="route('blog.category', $primaryCategory)"
                />
            </div>
        @endif

        <h3 class="text-lg font-semibold leading-snug text-white transition-colors group-hover:text-bulma-primary">
            <a href="{{ $link }}">
                {{ $post->title }}
            </a>
        </h3>

        <p class="text-sm leading-relaxed text-gray-400 line-clamp-2">
            {{ $excerpt }}
        </p>

        <div class="mt-auto flex items-center gap-2 pt-2 text-xs text-gray-500">
            @if($date)
                <time datetime="{{ $date->toAtomString() }}">{{ $date->translatedFormat('d \d\e M, Y') }}</time>
                <span aria-hidden="true">·</span>
            @endif
            <span>{{ $readingTime }} min de leitura</span>
        </div>
    </div>
</article>
