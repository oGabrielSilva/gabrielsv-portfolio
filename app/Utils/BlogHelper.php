<?php

namespace App\Utils;

class BlogHelper
{
    /**
     * Retorna a URL do blog interno.
     *
     * Quando $path é informado, monta a URL do post (rota blog.show com o slug).
     * Sem $path, devolve a listagem.
     */
    public static function getOwnerBlogURL(?string $path = null): string
    {
        if ($path) {
            return route('blog.show', ['post' => ltrim($path, '/')]);
        }

        return route('blog.index');
    }
}
