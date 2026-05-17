@props([
    'href' => null,
    'label' => '',
    'slug' => null,
    'color' => null,
    'icon' => null,
])

@php
    $resolvedColor = $color ?? ($slug ? (config('site.categories_colors.'.$slug, '#9ca3af')) : '#9ca3af');
    $tag = $href ? 'a' : 'span';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium transition-colors hover:bg-white/5 chip']) }}
    style="border-color: {{ $resolvedColor }}55; color: {{ $resolvedColor }};"
>
    <span class="size-1.5 shrink-0 rounded-full" style="background: {{ $resolvedColor }};"></span>
    <span>{{ $label ?: $slot }}</span>
</{{ $tag }}>
