<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OgImageController extends Controller
{
    private const WIDTH = 1200;
    private const HEIGHT = 630;

    public function post(Post $post): BinaryFileResponse
    {
        abort_unless($post->status === 'published', 404);

        $version = $post->updated_at?->timestamp ?? time();
        $cacheKey = "og/post-{$post->slug}-{$version}.png";
        $absolutePath = storage_path('app/public/'.$cacheKey);

        if (! is_file($absolutePath)) {
            @mkdir(dirname($absolutePath), 0775, true);
            $this->renderPng($post, $absolutePath);
        }

        if (! is_file($absolutePath)) {
            abort(500, 'Falha ao gerar OG image.');
        }

        return response()->file($absolutePath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function renderPng(Post $post, string $path): void
    {
        $primaryCategory = $post->categories->first();
        $accentHex = $primaryCategory
            ? config('site.categories_colors.'.$primaryCategory->slug, '#00d1b2')
            : '#00d1b2';

        $authorName = $post->author?->name ?? config('site.author.name');
        $title = (string) $post->title;
        $kindLabel = config('site.kind_labels.'.$post->kind, '');

        if (extension_loaded('imagick') && class_exists(\Imagick::class)) {
            $this->renderWithImagick($path, $title, $authorName, $accentHex, $kindLabel, $primaryCategory?->name);
        } else {
            $this->renderWithGd($path, $title, $authorName, $accentHex, $primaryCategory?->name);
        }
    }

    private function renderWithImagick(string $path, string $title, string $author, string $accent, string $kindLabel, ?string $categoryName): void
    {
        $img = new \Imagick;
        $img->newImage(self::WIDTH, self::HEIGHT, new \ImagickPixel('#0a0a0a'));
        $img->setImageFormat('png');

        $gradient = new \Imagick;
        $gradient->newPseudoImage(self::WIDTH, self::HEIGHT, sprintf(
            'gradient:%s-%s',
            $this->withAlpha($accent, 0.25),
            '#0a0a0a',
        ));
        $img->compositeImage($gradient, \Imagick::COMPOSITE_OVER, 0, 0);
        $gradient->clear();

        $draw = new \Imagick\ImagickDraw;
        $draw->setFillColor(new \ImagickPixel('#ffffff'));

        // Tagline (categoria/kind)
        $tagline = trim(($kindLabel ? $kindLabel.' · ' : '').($categoryName ?? config('app.name')));
        $draw->setFontSize(28);
        $draw->setFillColor(new \ImagickPixel($accent));
        $draw->annotation(70, 110, mb_strtoupper($tagline));

        // Título
        $draw->setFillColor(new \ImagickPixel('#ffffff'));
        $draw->setFontSize(72);
        $lines = $this->wrapText($title, 22);
        $y = 230;
        foreach (array_slice($lines, 0, 5) as $line) {
            $draw->annotation(70, $y, $line);
            $y += 90;
        }

        // Footer: autor + monograma GS
        $draw->setFontSize(26);
        $draw->setFillColor(new \ImagickPixel('#9ca3af'));
        $draw->annotation(70, self::HEIGHT - 60, $author);

        $draw->setFontSize(48);
        $draw->setFillColor(new \ImagickPixel($accent));
        $draw->annotation(self::WIDTH - 130, self::HEIGHT - 50, 'GS');

        $img->drawImage($draw);
        $img->writeImage($path);
        $img->clear();
    }

    private function renderWithGd(string $path, string $title, string $author, string $accent, ?string $categoryName): void
    {
        $img = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        $bg = imagecolorallocate($img, 10, 10, 10);
        imagefill($img, 0, 0, $bg);

        [$ar, $ag, $ab] = sscanf(ltrim($accent, '#'), '%02x%02x%02x');
        $accentColor = imagecolorallocate($img, $ar, $ag, $ab);
        $white = imagecolorallocate($img, 255, 255, 255);
        $gray = imagecolorallocate($img, 156, 163, 175);

        // Faixa lateral colorida
        imagefilledrectangle($img, 0, 0, 12, self::HEIGHT, $accentColor);

        // Categoria
        if ($categoryName) {
            imagestring($img, 5, 70, 90, mb_strtoupper($categoryName), $accentColor);
        }

        // Título (line wrap manual)
        $lines = $this->wrapText($title, 30);
        $y = 200;
        foreach (array_slice($lines, 0, 5) as $line) {
            imagestring($img, 5, 70, $y, $line, $white);
            $y += 50;
        }

        // Autor + GS
        imagestring($img, 4, 70, self::HEIGHT - 60, $author, $gray);
        imagestring($img, 5, self::WIDTH - 100, self::HEIGHT - 60, 'GS', $accentColor);

        imagepng($img, $path);
        imagedestroy($img);
    }

    private function wrapText(string $text, int $width): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $current = '';
        foreach ($words as $word) {
            if (mb_strlen($current.' '.$word) <= $width) {
                $current = $current === '' ? $word : $current.' '.$word;
            } else {
                if ($current !== '') {
                    $lines[] = $current;
                }
                $current = $word;
            }
        }
        if ($current !== '') {
            $lines[] = $current;
        }

        return $lines;
    }

    private function withAlpha(string $hex, float $alpha): string
    {
        $hex = ltrim($hex, '#');
        [$r, $g, $b] = sscanf($hex, '%02x%02x%02x');

        return sprintf('rgba(%d,%d,%d,%.2f)', $r, $g, $b, $alpha);
    }
}
