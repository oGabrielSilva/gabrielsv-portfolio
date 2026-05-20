@props(['name', 'class' => 'size-5'])

@php
    static $cache = [];

    $labels = [
        'github' => 'GitHub',
        'linkedin' => 'LinkedIn',
        'x' => 'X (Twitter)',
        'twitter' => 'X (Twitter)',
        'email' => 'E-mail',
    ];

    // Aliases pra slugs de arquivo.
    $fileMap = [
        'twitter' => 'x',
    ];
    $slug = $fileMap[$name] ?? $name;
    $path = resource_path('icons/'.$slug.'.svg');

    if (! isset($cache[$slug])) {
        $cache[$slug] = is_file($path) ? trim(file_get_contents($path)) : null;
    }

    $svg = $cache[$slug];

    // Injeta class + aria-label no <svg> raiz.
    if ($svg) {
        $label = $labels[$name] ?? ucfirst($name);
        $svg = preg_replace(
            '/<svg\b([^>]*)>/i',
            '<svg$1 class="'.e($class).'" role="img" aria-label="'.e($label).'">',
            $svg,
            1
        );
    }
@endphp

@if($svg)
    {!! $svg !!}
@endif
