<?php

namespace App\Services;

use App\Models\Post;

class JsonLdBuilder
{
    public function forPost(Post $post): array
    {
        $author = $post->author;
        $primaryCategory = $post->categories->first();
        $tags = $post->categories->pluck('name')->merge($post->tags->pluck('name'))->unique()->values()->all();

        $image = route('og.post', $post);
        $coverUrl = $post->getFirstMediaUrl('cover');
        if ($coverUrl !== '') {
            $image = $coverUrl;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->meta_description ?: $post->excerpt ?: $post->title,
            'image' => $image,
            'datePublished' => $post->published_at?->toAtomString(),
            'dateModified' => $post->updated_at?->toAtomString(),
            'wordCount' => (int) $post->reading_time * 200,
            'timeRequired' => 'PT'.(int) $post->reading_time.'M',
            'articleSection' => $primaryCategory?->name,
            'keywords' => implode(', ', $tags),
            'inLanguage' => 'pt-BR',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $post),
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $author?->name ?? config('site.author.name'),
                'url' => url('/sobre'),
                'sameAs' => array_values(array_filter([
                    config('site.social.github'),
                    config('site.social.linkedin'),
                ])),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('favicon.svg'),
                ],
            ],
        ];
    }

    public function forWebsite(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => url('/'),
            'inLanguage' => 'pt-BR',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/blog?q={search_term_string}'),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public function forPerson(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => config('site.author.name'),
            'jobTitle' => config('site.author.role'),
            'description' => config('site.author.bio'),
            'url' => url('/sobre'),
            'sameAs' => array_values(array_filter([
                config('site.social.github'),
                config('site.social.linkedin'),
            ])),
        ];
    }

    public function breadcrumbs(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(function (array $item, int $i) {
                return [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $item['name'],
                    'item' => $item['url'] ?? null,
                ];
            })->all(),
        ];
    }
}
