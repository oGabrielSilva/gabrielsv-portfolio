@props(['previous' => null, 'next' => null])

@if($previous || $next)
    <nav aria-label="Navegação entre posts" class="grid gap-4 sm:grid-cols-2">
        @if($previous)
            <a
                href="{{ route('blog.show', $previous) }}"
                class="group flex flex-col gap-2 rounded-2xl border border-neutral-800 bg-neutral-900/40 p-4 transition-colors hover:border-bulma-primary/40 sm:p-5"
            >
                <span class="flex items-center gap-2 text-xs uppercase tracking-wide text-gray-500">
                    <i data-lucide="arrow-left" class="size-3.5"></i>
                    Post anterior
                </span>
                <span class="text-sm font-semibold leading-snug text-white transition-colors group-hover:text-bulma-primary">
                    {{ $previous->title }}
                </span>
            </a>
        @else
            <div></div>
        @endif

        @if($next)
            <a
                href="{{ route('blog.show', $next) }}"
                class="group flex flex-col items-end gap-2 rounded-2xl border border-neutral-800 bg-neutral-900/40 p-4 text-right transition-colors hover:border-bulma-primary/40 sm:p-5"
            >
                <span class="flex items-center gap-2 text-xs uppercase tracking-wide text-gray-500">
                    Próximo post
                    <i data-lucide="arrow-right" class="size-3.5"></i>
                </span>
                <span class="text-sm font-semibold leading-snug text-white transition-colors group-hover:text-bulma-primary">
                    {{ $next->title }}
                </span>
            </a>
        @endif
    </nav>
@endif
