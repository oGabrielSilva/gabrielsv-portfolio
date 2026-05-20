@props(['author' => null])

@php
    $name = $author?->name ?? config('site.author.name');
    $bio = $author?->bio ?? config('site.author.bio');
    $avatar = $author?->avatar_url ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(config('site.author.email'))).'?d=mp&s=200';

    $social = config('site.social', []);
    $socialIcons = collect([
        'github' => ['hover' => 'hover:text-bulma-primary', 'label' => 'GitHub'],
        'linkedin' => ['hover' => 'hover:text-bulma-link', 'label' => 'LinkedIn'],
        'x' => ['hover' => 'hover:text-white', 'label' => 'X (Twitter)'],
        'email' => ['hover' => 'hover:text-bulma-primary', 'label' => 'E-mail'],
    ])->filter(fn ($_, $key) => ! empty($social[$key]));
@endphp

<aside class="rounded-2xl border border-neutral-800 bg-neutral-900/60 p-6 sm:p-8">
    <div class="flex flex-col items-start gap-4 sm:flex-row sm:gap-6">
        <img
            src="{{ $avatar }}"
            alt="{{ $name }}"
            class="size-16 shrink-0 rounded-full ring-2 ring-bulma-primary/30 sm:size-20"
            loading="lazy"
            decoding="async"
            width="80"
            height="80"
        >

        <div class="flex-1 space-y-2">
            <p class="text-xs uppercase tracking-wide text-bulma-primary">Escrito por</p>
            <h3 class="text-lg font-semibold text-white">{{ $name }}</h3>
            @if($bio)
                <p class="text-sm leading-relaxed text-gray-400">{{ $bio }}</p>
            @endif

            <div class="flex flex-wrap items-center gap-4 pt-2">
                @foreach($socialIcons as $key => $meta)
                    <a
                        href="{{ $social[$key] }}"
                        @if($key !== 'email') target="_blank" rel="noopener me" @endif
                        class="text-gray-500 transition-colors {{ $meta['hover'] }}"
                        aria-label="{{ $meta['label'] }}"
                        title="{{ $meta['label'] }}"
                    >
                        <x-icon-brand :name="$key" class="size-4.5" />
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</aside>
