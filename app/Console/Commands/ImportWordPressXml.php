<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

class ImportWordPressXml extends Command
{
    protected $signature = 'blog:import-wp
                            {file : Caminho para o arquivo WXR exportado do WordPress}
                            {--author= : E-mail do User a usar como autor (cria se não existir)}
                            {--dry-run : Apenas exibe o que seria importado, sem gravar}
                            {--update : Atualiza posts existentes (mesmo slug) em vez de pular}';

    protected $description = 'Importa posts de um export WXR do WordPress (categorias, tags, imagens inline e capa).';

    private int $importedPosts = 0;

    private int $skippedPosts = 0;

    private int $downloadedImages = 0;

    private int $failedImages = 0;

    private array $warnings = [];

    /** @var array<string, int> Cache de attachment_url => attachment_id do WXR para resolver featured image */
    private array $attachmentsById = [];

    /** @var array<int, string> Map id => URL */
    private array $attachmentUrlById = [];

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! is_file($file)) {
            $this->error("Arquivo não encontrado: {$file}");

            return self::FAILURE;
        }

        $authorEmail = $this->option('author');
        if (! $authorEmail) {
            $this->error('Informe --author=email@exemplo.com');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $update = (bool) $this->option('update');

        if ($dryRun) {
            $this->warn('Modo dry-run: nada será gravado.');
        }

        $xml = simplexml_load_file($file, SimpleXMLElement::class, LIBXML_NOCDATA);
        if (! $xml) {
            $this->error('Falha ao parsear o XML.');

            return self::FAILURE;
        }

        $namespaces = $xml->getDocNamespaces(true);

        $author = $this->resolveAuthor($authorEmail, $dryRun);

        $this->indexAttachments($xml, $namespaces);

        $items = $xml->channel->item ?? [];
        $this->info('Itens encontrados no XML: '.count($items));

        foreach ($items as $item) {
            $wp = $item->children($namespaces['wp'] ?? '');
            $content = $item->children($namespaces['content'] ?? '');
            $excerpt = $item->children($namespaces['excerpt'] ?? '');

            $postType = (string) ($wp->post_type ?? '');
            $status = (string) ($wp->status ?? '');

            if ($postType !== 'post' || $status !== 'publish') {
                continue;
            }

            try {
                $this->importPost($item, $wp, $content, $excerpt, $author, $namespaces, $dryRun, $update);
            } catch (Throwable $e) {
                $this->error('Erro importando "'.(string) $item->title.'": '.$e->getMessage());
                Log::error('blog:import-wp falhou em um post', ['exception' => $e]);
            }
        }

        $this->newLine();
        $this->line("<info>Posts importados:</info> {$this->importedPosts}");
        $this->line("<comment>Posts pulados (já existiam):</comment> {$this->skippedPosts}");
        $this->line("<info>Imagens baixadas:</info> {$this->downloadedImages}");
        if ($this->failedImages > 0) {
            $this->line("<error>Imagens falhadas:</error> {$this->failedImages}");
        }

        if ($this->warnings) {
            $this->newLine();
            $this->warn('Avisos:');
            foreach ($this->warnings as $w) {
                $this->line(" - {$w}");
            }
        }

        return self::SUCCESS;
    }

    private function resolveAuthor(string $email, bool $dryRun): ?User
    {
        $user = User::firstWhere('email', $email);
        if ($user) {
            return $user;
        }

        if ($dryRun) {
            $this->line("Autor {$email} seria criado (dry-run).");

            return null;
        }

        return User::create([
            'name' => Str::before($email, '@'),
            'email' => $email,
            'password' => Hash::make(Str::random(40)),
            'email_verified_at' => now(),
        ]);
    }

    private function indexAttachments(SimpleXMLElement $xml, array $namespaces): void
    {
        $wpNs = $namespaces['wp'] ?? '';

        foreach ($xml->channel->item ?? [] as $item) {
            $wp = $item->children($wpNs);
            if ((string) ($wp->post_type ?? '') !== 'attachment') {
                continue;
            }
            $id = (int) ($wp->post_id ?? 0);
            $url = (string) ($wp->attachment_url ?? '');
            if ($id && $url) {
                $this->attachmentUrlById[$id] = $url;
            }
        }
    }

    private function importPost(
        SimpleXMLElement $item,
        SimpleXMLElement $wp,
        SimpleXMLElement $content,
        SimpleXMLElement $excerpt,
        ?User $author,
        array $namespaces,
        bool $dryRun,
        bool $update,
    ): void {
        $title = trim((string) $item->title);
        $slug = (string) $wp->post_name;
        if (! $slug) {
            $slug = Str::slug($title);
        }

        $bodyHtml = (string) ($content->encoded ?? '');
        $excerptText = trim((string) ($excerpt->encoded ?? ''));
        $publishedAt = $this->parseDate((string) $wp->post_date_gmt);

        $existing = Post::withTrashed()->where('slug', $slug)->first();

        if ($existing && ! $update) {
            $this->skippedPosts++;
            $this->line("- pulado (já existe): {$slug}");

            return;
        }

        if ($dryRun) {
            $this->line("+ seria importado: {$slug} — {$title}");
            $this->importedPosts++;

            return;
        }

        $post = $existing ?: new Post;
        $post->fill([
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $excerptText ?: null,
            'body_html' => $bodyHtml,
            'status' => 'published',
            'published_at' => $publishedAt,
            'author_id' => $author?->id,
        ]);

        if (Str::contains($bodyHtml, '[') && preg_match('/\[[a-z][\w-]*[\s\]]/i', $bodyHtml)) {
            $post->status = 'draft';
            $this->warnings[] = "{$slug}: contém shortcodes — importado como rascunho.";
        }

        $post->save();

        $this->attachTaxonomies($item, $post, $namespaces);

        // Imagens inline no corpo
        $newHtml = $this->rewriteInlineImages($post, $bodyHtml);
        if ($newHtml !== $bodyHtml) {
            $post->body_html = $newHtml;
            $post->save();
        }

        // Imagem destacada (cover)
        $this->importFeaturedImage($post, $wp, $namespaces);

        $this->importedPosts++;
        $this->line("+ importado: {$slug}");
    }

    private function attachTaxonomies(SimpleXMLElement $item, Post $post, array $namespaces): void
    {
        $categoryIds = [];
        $tagIds = [];

        foreach ($item->category as $cat) {
            $domain = (string) $cat['domain'];
            $name = trim((string) $cat);
            $nicename = (string) $cat['nicename'];
            $slug = $nicename ?: Str::slug($name);

            if ($domain === 'category') {
                $model = Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
                $categoryIds[] = $model->id;
            } elseif ($domain === 'post_tag') {
                $model = Tag::firstOrCreate(['slug' => $slug], ['name' => $name]);
                $tagIds[] = $model->id;
            }
        }

        if ($categoryIds) {
            $post->categories()->sync($categoryIds);
        }
        if ($tagIds) {
            $post->tags()->sync($tagIds);
        }
    }

    private function rewriteInlineImages(Post $post, string $html): string
    {
        if (! Str::contains($html, '<img')) {
            return $html;
        }

        $doc = new DOMDocument;
        // Suprime warnings de HTML mal formado do WP
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $doc->getElementsByTagName('img');
        $changed = false;

        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (! $src || ! Str::startsWith($src, ['http://', 'https://'])) {
                continue;
            }

            $newUrl = $this->downloadImageToMedia($post, $src);
            if ($newUrl && $newUrl !== $src) {
                $img->setAttribute('src', $newUrl);
                $changed = true;
            }
        }

        if (! $changed) {
            return $html;
        }

        return $doc->saveHTML();
    }

    private function importFeaturedImage(Post $post, SimpleXMLElement $wp, array $namespaces): void
    {
        $thumbnailId = null;
        foreach ($wp->postmeta ?? [] as $meta) {
            if ((string) $meta->meta_key === '_thumbnail_id') {
                $thumbnailId = (int) $meta->meta_value;
                break;
            }
        }

        if (! $thumbnailId) {
            return;
        }

        $url = $this->attachmentUrlById[$thumbnailId] ?? null;
        if (! $url) {
            $this->warnings[] = "{$post->slug}: thumbnail id {$thumbnailId} sem URL no XML.";

            return;
        }

        $this->downloadImageToCollection($post, $url, 'cover');
    }

    private function downloadImageToMedia(Post $post, string $url): ?string
    {
        return $this->downloadImageToCollection($post, $url, 'content');
    }

    private function downloadImageToCollection(Post $post, string $url, string $collection): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);
            if (! $response->successful()) {
                $this->failedImages++;
                $this->warnings[] = "{$post->slug}: HTTP {$response->status()} ao baixar {$url}";

                return null;
            }

            $filename = basename(parse_url($url, PHP_URL_PATH) ?: 'image');
            $media = $post
                ->addMediaFromString($response->body())
                ->usingFileName($filename)
                ->toMediaCollection($collection);

            $this->downloadedImages++;

            return $media->getFullUrl();
        } catch (Throwable $e) {
            $this->failedImages++;
            $this->warnings[] = "{$post->slug}: erro baixando {$url} — {$e->getMessage()}";

            return null;
        }
    }

    private function parseDate(?string $date): ?Carbon
    {
        if (! $date || $date === '0000-00-00 00:00:00') {
            return null;
        }

        try {
            return Carbon::parse($date.' UTC');
        } catch (Throwable) {
            return null;
        }
    }
}
