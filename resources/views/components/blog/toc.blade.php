@props(['toc' => []])

@if(count($toc) >= 2)
    <aside class="toc lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:overflow-y-auto">
        <details class="group rounded-xl border border-neutral-800 bg-neutral-900/40 p-4 lg:border-0 lg:bg-transparent lg:p-0" open>
            <summary class="flex cursor-pointer items-center justify-between text-xs font-semibold uppercase tracking-wide text-gray-500 lg:cursor-default lg:pointer-events-none">
                <span class="flex items-center gap-2">
                    <i data-lucide="list" class="size-3.5"></i>
                    Nesta página
                </span>
                <i data-lucide="chevron-down" class="size-4 transition-transform group-open:rotate-180 lg:hidden"></i>
            </summary>

            <nav class="mt-3" aria-label="Tabela de conteúdo">
                <ul class="space-y-1.5 text-sm border-l border-neutral-800 pl-3">
                    @foreach($toc as $item)
                        <li class="toc__item" data-toc-level="{{ $item['level'] }}" @if($item['level'] >= 3) style="padding-left: {{ ($item['level'] - 2) * 0.75 }}rem;" @endif>
                            <a
                                href="#{{ $item['id'] }}"
                                data-toc-link="{{ $item['id'] }}"
                                class="toc__link block py-1 text-gray-400 transition-colors hover:text-bulma-primary"
                            >
                                {{ $item['text'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </details>
    </aside>
@endif
