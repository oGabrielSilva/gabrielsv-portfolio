<?php

/**
 * Gera variantes responsivas + WebP da imagem hero "Quem sou eu".
 *
 * Input:  public/IMG_20241226_184124563.jpg (2450x2448, 1.25 MB)
 * Output: public/img/hero-{512,1024}.{webp,jpg} — exibido em 256-320px (1x/2x)
 *
 * Rodar: php scripts/generate-hero-images.php
 */

$source = __DIR__ . '/../public/IMG_20241226_184124563.jpg';
$outputDir = __DIR__ . '/../public/img';

if (!file_exists($source)) {
    fwrite(STDERR, "Imagem fonte não encontrada: $source\n");
    exit(1);
}

if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true)) {
    fwrite(STDERR, "Não consegui criar $outputDir\n");
    exit(1);
}

$src = imagecreatefromjpeg($source);
if (!$src) {
    fwrite(STDERR, "Falha ao carregar JPEG\n");
    exit(1);
}

// O JPEG fonte está fisicamente deitado (selfie de celular) e GD ignora EXIF —
// rotacionamos para deixar a cabeça pra cima nas variantes geradas.
$src = imagerotate($src, 90, 0);

$srcW = imagesx($src);
$srcH = imagesy($src);
echo "Rotacionado -90° (compensa EXIF). Novas dims: {$srcW}×{$srcH}\n";

$variants = [
    ['width' => 512, 'webpQuality' => 82, 'jpgQuality' => 80],
    ['width' => 896, 'webpQuality' => 80, 'jpgQuality' => 78],
    ['width' => 1024, 'webpQuality' => 78, 'jpgQuality' => 78],
];

foreach ($variants as $v) {
    $targetW = $v['width'];
    $targetH = (int) round($srcH * ($targetW / $srcW));

    $dst = imagecreatetruecolor($targetW, $targetH);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetW, $targetH, $srcW, $srcH);

    $webpPath = "$outputDir/hero-$targetW.webp";
    $jpgPath = "$outputDir/hero-$targetW.jpg";

    imagewebp($dst, $webpPath, $v['webpQuality']);
    imagejpeg($dst, $jpgPath, $v['jpgQuality']);
    imagedestroy($dst);

    $webpKb = round(filesize($webpPath) / 1024, 1);
    $jpgKb = round(filesize($jpgPath) / 1024, 1);
    echo "  {$targetW}×{$targetH}: hero-$targetW.webp ({$webpKb} KB) | hero-$targetW.jpg ({$jpgKb} KB)\n";
}

imagedestroy($src);

$origKb = round(filesize($source) / 1024, 1);
echo "\nOriginal: {$srcW}×{$srcH} ({$origKb} KB)\n";
echo "Done.\n";
