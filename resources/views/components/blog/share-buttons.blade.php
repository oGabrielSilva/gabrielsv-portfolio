@props(['post'])

@php
    $url = route('blog.show', $post);
    $title = $post->title;
    $twitter = 'https://twitter.com/intent/tweet?'.http_build_query(['text' => $title, 'url' => $url]);
    $linkedin = 'https://www.linkedin.com/sharing/share-offsite/?'.http_build_query(['url' => $url]);
@endphp

<div class="flex flex-wrap items-center gap-2" data-share-buttons>
    <span class="text-xs uppercase tracking-wide text-gray-500">Compartilhar:</span>

    <a
        href="{{ $twitter }}"
        target="_blank"
        rel="noopener"
        class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900 px-3 py-1.5 text-xs text-gray-300 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary"
        aria-label="Compartilhar no Twitter/X"
    >
        <i data-lucide="twitter" class="size-3.5"></i>
        <span>X</span>
    </a>

    <a
        href="{{ $linkedin }}"
        target="_blank"
        rel="noopener"
        class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900 px-3 py-1.5 text-xs text-gray-300 transition-colors hover:border-bulma-link/40 hover:text-bulma-link"
        aria-label="Compartilhar no LinkedIn"
    >
        <i data-lucide="linkedin" class="size-3.5"></i>
        <span>LinkedIn</span>
    </a>

    <button
        type="button"
        data-copy-url="{{ $url }}"
        class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900 px-3 py-1.5 text-xs text-gray-300 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary"
        aria-label="Copiar link do post"
    >
        <i data-lucide="link" class="size-3.5"></i>
        <span data-copy-label>Copiar link</span>
    </button>
</div>
