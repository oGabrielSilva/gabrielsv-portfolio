@props(['author' => null])

@php
    $name = $author?->name ?? config('site.author.name');
    $bio = $author?->bio ?? config('site.author.bio');
    $avatar = $author?->avatar_url ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(config('site.author.email'))).'?d=mp&s=200';
    $github = config('site.social.github');
    $linkedin = config('site.social.linkedin');
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

            <div class="flex flex-wrap items-center gap-3 pt-2">
                @if($github)
                    <a href="{{ $github }}" target="_blank" rel="noopener" class="text-gray-500 transition-colors hover:text-bulma-primary" aria-label="GitHub">
                        <x-icon-brand name="github" class="size-4" />
                    </a>
                @endif
                @if($linkedin)
                    <a href="{{ $linkedin }}" target="_blank" rel="noopener" class="text-gray-500 transition-colors hover:text-bulma-link" aria-label="LinkedIn">
                        <x-icon-brand name="linkedin" class="size-4" />
                    </a>
                @endif
            </div>
        </div>
    </div>
</aside>
