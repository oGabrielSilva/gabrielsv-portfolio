@props(['posts'])

@if($posts && $posts->count() > 0)
    <section aria-labelledby="related-heading" class="space-y-4">
        <h2 id="related-heading" class="text-sm font-semibold uppercase tracking-wide text-gray-500">
            Continua a leitura
        </h2>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $related)
                @php
                    $cover = $related->getFirstMediaUrl('cover');
                    $primaryCategory = $related->categories->first();
                @endphp
                <a
                    href="{{ route('blog.show', $related) }}"
                    class="group spotlight flex flex-col gap-3 rounded-2xl border border-neutral-800 bg-neutral-900/40 p-4 transition-colors hover:border-bulma-primary/40"
                >
                    @if($cover)
                        <div class="aspect-video overflow-hidden rounded-lg bg-neutral-800">
                            <img
                                src="{{ $cover }}"
                                alt=""
                                class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                    @endif

                    <div class="flex flex-col gap-2">
                        @if($primaryCategory)
                            <x-blog.chip
                                :label="$primaryCategory->name"
                                :slug="$primaryCategory->slug"
                                :href="route('blog.category', $primaryCategory)"
                            />
                        @endif

                        <h3 class="text-base font-semibold leading-snug text-white transition-colors group-hover:text-bulma-primary">
                            {{ $related->title }}
                        </h3>

                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <span>{{ $related->reading_time ?? 1 }} min</span>
                            @if($related->published_at)
                                <span aria-hidden="true">·</span>
                                <time datetime="{{ $related->published_at->toAtomString() }}">
                                    {{ $related->published_at->translatedFormat('d/m/Y') }}
                                </time>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif
