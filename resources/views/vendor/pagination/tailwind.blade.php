@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginação" class="flex flex-wrap items-center justify-between gap-4 border-t border-neutral-800 pt-6">
        {{-- Texto de status --}}
        <p class="text-xs text-gray-500" role="status">
            @if ($paginator->total() > 0)
                Mostrando
                <span class="font-medium text-gray-300">{{ $paginator->firstItem() }}</span>
                a
                <span class="font-medium text-gray-300">{{ $paginator->lastItem() }}</span>
                de
                <span class="font-medium text-gray-300">{{ $paginator->total() }}</span>
            @endif
        </p>

        {{-- Botões prev/next + indicador --}}
        <div class="flex items-center gap-3">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900/40 px-3 py-1.5 text-xs text-neutral-700">
                    <i data-lucide="arrow-left" class="size-3.5"></i>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900 px-3 py-1.5 text-xs text-gray-300 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary">
                    <i data-lucide="arrow-left" class="size-3.5"></i>
                    Anterior
                </a>
            @endif

            <span class="font-mono text-xs text-gray-500" aria-hidden="true">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900 px-3 py-1.5 text-xs text-gray-300 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary">
                    Próxima
                    <i data-lucide="arrow-right" class="size-3.5"></i>
                </a>
            @else
                <span aria-disabled="true" class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900/40 px-3 py-1.5 text-xs text-neutral-700">
                    Próxima
                    <i data-lucide="arrow-right" class="size-3.5"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
