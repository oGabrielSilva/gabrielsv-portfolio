@props(['post'])

@php
    $author = $post->author;
    $authorName = $author?->name ?? config('site.author.name');
    $avatar = $author?->avatar_url ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(config('site.author.email'))).'?d=mp&s=200';
    $publishedAt = $post->published_at;
    $updatedAt = $post->updated_at;
    $wasUpdated = $publishedAt && $updatedAt && $updatedAt->diffInHours($publishedAt) > 1;
@endphp

<div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-400">
    <div class="flex items-center gap-2">
        <img src="{{ $avatar }}" alt="{{ $authorName }}" class="size-8 rounded-full ring-1 ring-white/10" loading="lazy" decoding="async" width="32" height="32">
        <span class="font-medium text-gray-200">{{ $authorName }}</span>
    </div>

    @if($publishedAt)
        <span aria-hidden="true" class="text-gray-700">·</span>
        <time datetime="{{ $publishedAt->toAtomString() }}">
            {{ $publishedAt->translatedFormat('d \d\e F \d\e Y') }}
        </time>
    @endif

    @if($wasUpdated)
        <span aria-hidden="true" class="text-gray-700">·</span>
        <span class="text-gray-500">
            Atualizado em
            <time datetime="{{ $updatedAt->toAtomString() }}">{{ $updatedAt->translatedFormat('d/m/Y') }}</time>
        </span>
    @endif

    @if($post->reading_time)
        <span aria-hidden="true" class="text-gray-700">·</span>
        <span class="text-gray-500">{{ $post->reading_time }} min de leitura</span>
    @endif
</div>
