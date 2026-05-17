@props(['post', 'seriesPosts'])

@if($post->series_slug && $seriesPosts && $seriesPosts->count() > 1)
    @php
        $currentIndex = $seriesPosts->search(fn ($p) => $p->id === $post->id);
        $total = $seriesPosts->count();
        $position = $currentIndex !== false ? $currentIndex + 1 : null;
    @endphp

    <section aria-labelledby="series-heading" class="rounded-2xl border border-bulma-primary/20 bg-bulma-primary/5 p-5 sm:p-6">
        <div class="flex items-start gap-3">
            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-bulma-primary/20 text-bulma-primary">
                <i data-lucide="list-ordered" class="size-4"></i>
            </div>
            <div class="flex-1 space-y-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-bulma-primary">Parte de uma série</p>
                    <h2 id="series-heading" class="mt-1 text-base font-semibold text-white">
                        {{ $position ? "Parte {$position} de {$total}" : "Série: {$post->series_slug}" }}
                    </h2>
                </div>

                <ol class="space-y-1.5">
                    @foreach($seriesPosts as $i => $sibling)
                        <li>
                            @if($sibling->id === $post->id)
                                <span class="flex items-start gap-2 text-sm text-bulma-primary">
                                    <span class="mt-0.5 inline-block size-1.5 shrink-0 rounded-full bg-bulma-primary"></span>
                                    <span class="font-medium">{{ $i + 1 }}. {{ $sibling->title }}</span>
                                </span>
                            @else
                                <a href="{{ route('blog.show', $sibling) }}" class="flex items-start gap-2 text-sm text-gray-400 transition-colors hover:text-white">
                                    <span class="mt-0.5 inline-block size-1.5 shrink-0 rounded-full bg-gray-700"></span>
                                    <span>{{ $i + 1 }}. {{ $sibling->title }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>
@endif
