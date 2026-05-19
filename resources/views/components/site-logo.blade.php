@props(['class' => 'w-8 h-8 inline-block'])

<picture>
    <source type="image/webp" srcset="/img/logo-64.webp">
    <img src="/img/logo-64.png" alt="Logo" {{ $attributes->merge(['class' => $class]) }} width="32" height="32" fetchpriority="high" decoding="async">
</picture>
