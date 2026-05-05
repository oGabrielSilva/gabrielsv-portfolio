<?php

namespace App\Utils;

class BlogHelper
{

    public static function getOwnerBlogURL(string $path = null)
    {
        $url = config('app.owner_blog_url');

        if (!$url)
            return '';

        $url = rtrim($url, '/');

        if ($path) {
            $url .= '/' . ltrim($path, '/');
        }

        return $url;
    }
}
