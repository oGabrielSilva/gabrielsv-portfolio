<?php

return [
    'feeds' => [
        'rss' => [
            'items' => [\App\Models\Post::class, 'getFeedItems'],
            'url' => '/feed.xml',
            'title' => config('app.name').' — Blog',
            'description' => 'Ensaios, notas e craft sobre desenvolvimento web por Gabriel Henrique da Silva.',
            'language' => 'pt-BR',
            'image' => '',
            'format' => 'rss',
            'view' => 'feed::rss',
            'type' => '',
            'contentType' => '',
        ],
        'atom' => [
            'items' => [\App\Models\Post::class, 'getFeedItems'],
            'url' => '/atom.xml',
            'title' => config('app.name').' — Blog',
            'description' => 'Ensaios, notas e craft sobre desenvolvimento web por Gabriel Henrique da Silva.',
            'language' => 'pt-BR',
            'image' => '',
            'format' => 'atom',
            'view' => 'feed::atom',
            'type' => '',
            'contentType' => '',
        ],
        'json' => [
            'items' => [\App\Models\Post::class, 'getFeedItems'],
            'url' => '/feed.json',
            'title' => config('app.name').' — Blog',
            'description' => 'Ensaios, notas e craft sobre desenvolvimento web por Gabriel Henrique da Silva.',
            'language' => 'pt-BR',
            'image' => '',
            'format' => 'json',
            'view' => 'feed::json',
            'type' => '',
            'contentType' => '',
        ],
    ],
];
