<?php

namespace App\Filament\Resources\Posts\Support;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\HtmlImportService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PostModalSchemas
{
    /**
     * Action que abre modal de Publicação (status, data, slug, autor).
     */
    public static function publication(): Action
    {
        return Action::make('publication')
            ->label('Publicação')
            ->icon(Heroicon::OutlinedRocketLaunch)
            ->modalHeading('Publicação')
            ->modalWidth('md')
            ->modalSubmitActionLabel('Salvar')
            ->fillForm(fn (Post $record): array => [
                'slug' => $record->slug,
                'status' => $record->status,
                'featured' => (bool) $record->featured,
                'published_at' => $record->published_at,
                'author_id' => $record->author_id,
            ])
            ->schema([
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Aparece em /blog/{slug}.')
                    ->unique(table: 'posts', column: 'slug', ignorable: fn (Post $record) => $record),
                Toggle::make('featured')
                    ->label('Destacar')
                    ->helperText('Aparece no card grande no topo da listagem.'),
                Select::make('status')
                    ->options(['draft' => 'Rascunho', 'published' => 'Publicado'])
                    ->required()
                    ->native(false),
                DateTimePicker::make('published_at')
                    ->label('Publicado em')
                    ->helperText('Data futura agenda; em branco usa "agora" ao publicar.'),
                Select::make('author_id')
                    ->label('Autor')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->action(function (array $data, Post $record): void {
                if ($data['status'] === 'published' && empty($data['published_at'])) {
                    $data['published_at'] = now();
                }
                $record->update($data);
            });
    }

    /**
     * Action que abre modal de Capa (cover image + texto alternativo).
     */
    public static function cover(): Action
    {
        return Action::make('cover')
            ->label('Capa')
            ->icon(Heroicon::OutlinedPhoto)
            ->modalHeading('Capa do post')
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Salvar')
            ->schema([
                SpatieMediaLibraryFileUpload::make('cover')
                    ->label('Imagem')
                    ->collection('cover')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions(['16:9', '4:3', '1:1']),
                TextInput::make('cover_alt')
                    ->label('Texto alternativo (alt)')
                    ->maxLength(255)
                    ->helperText('Descreve a imagem pra leitores de tela e SEO. Em branco usa o título do post.'),
            ])
            ->fillForm(fn (Post $record): array => [
                'cover' => $record->getFirstMedia('cover')?->uuid,
                'cover_alt' => $record->getFirstMedia('cover')?->getCustomProperty('alt'),
            ])
            ->action(function (array $data, Post $record): void {
                $media = $record->getFirstMedia('cover');
                if ($media) {
                    $media->setCustomProperty('alt', $data['cover_alt'] ?? null);
                    $media->save();
                }
            });
    }

    /**
     * Action que abre modal de Taxonomia (categorias e tags).
     *
     * Os dois Selects usam ->options() carregadas explicitamente (sem ->relationship())
     * porque dentro de uma Action o auto-sync do relationship pode bater de frente
     * com o action() callback e gravar array vazio.
     *
     * O createOptionForm das tags aceita "nome ou lista separada por vírgula"
     * pra criar várias de uma vez. Retorna o id da última criada (limitação do
     * Filament: createOptionUsing devolve um id) e as outras já viram entradas
     * do Select via reload do options() na próxima abertura da modal.
     */
    public static function taxonomy(): Action
    {
        return Action::make('taxonomy')
            ->label('Taxonomia')
            ->icon(Heroicon::OutlinedTag)
            ->modalHeading('Categorias e tags')
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Salvar')
            ->fillForm(fn (Post $record): array => [
                'categories' => $record->categories->pluck('id')->map(fn ($id) => (string) $id)->toArray(),
                'tags' => $record->tags->pluck('id')->map(fn ($id) => (string) $id)->toArray(),
            ])
            ->schema([
                Select::make('categories')
                    ->label('Categorias')
                    ->multiple()
                    ->options(fn () => Category::orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->required()->unique(table: 'categories', column: 'slug', ignoreRecord: false),
                    ])
                    ->createOptionUsing(fn (array $data) => Category::create($data)->getKey()),
                Select::make('tags')
                    ->label('Tags existentes')
                    ->multiple()
                    ->options(fn () => Tag::orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->required()->unique(table: 'tags', column: 'slug', ignoreRecord: false),
                    ])
                    ->createOptionUsing(fn (array $data) => Tag::create($data)->getKey()),
                TextInput::make('new_tags')
                    ->label('Criar várias tags de uma vez')
                    ->placeholder('laravel, php, api, redis')
                    ->helperText('Separe por vírgula. As novas serão criadas e anexadas ao post junto com as selecionadas acima.'),
            ])
            ->action(function (array $data, Post $record): void {
                $categoryIds = collect($data['categories'] ?? [])
                    ->filter()
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->all();
                $record->categories()->sync($categoryIds);

                // IDs do Select (tags existentes selecionadas)
                $selectedTagIds = collect($data['tags'] ?? [])
                    ->filter()
                    ->map(fn ($id) => (int) $id);

                // IDs das tags criadas via campo "new_tags" (firstOrCreate por slug)
                $newTagIds = collect(explode(',', (string) ($data['new_tags'] ?? '')))
                    ->map(fn ($n) => trim($n))
                    ->filter()
                    ->unique()
                    ->map(fn (string $name) => Tag::firstOrCreate(
                        ['slug' => Str::slug($name)],
                        ['name' => $name]
                    )->getKey());

                $tagIds = $selectedTagIds->merge($newTagIds)->unique()->values()->all();
                $record->tags()->sync($tagIds);
            });
    }

    /**
     * Action que abre modal de SEO (excerpt, meta_title, meta_description).
     */
    public static function seo(): Action
    {
        return Action::make('seo')
            ->label('SEO')
            ->icon(Heroicon::OutlinedMagnifyingGlass)
            ->modalHeading('SEO')
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Salvar')
            ->fillForm(fn (Post $record): array => [
                'excerpt' => $record->excerpt,
                'meta_title' => $record->meta_title,
                'meta_description' => $record->meta_description,
            ])
            ->schema([
                Textarea::make('excerpt')
                    ->label('Resumo')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Usado na listagem e como meta description quando essa não estiver preenchida.'),
                TextInput::make('meta_title')
                    ->maxLength(70)
                    ->helperText('Até 70 caracteres. Em branco usa o title.'),
                Textarea::make('meta_description')
                    ->rows(2)
                    ->maxLength(160)
                    ->helperText('Até 160 caracteres. Em branco usa o resumo.'),
            ])
            ->action(function (array $data, Post $record): void {
                $record->update($data);
            });
    }

    /**
     * Action que abre modal de Série (agrupa posts em sequência).
     */
    public static function series(): Action
    {
        return Action::make('series')
            ->label('Série')
            ->icon(Heroicon::OutlinedQueueList)
            ->modalHeading('Série de posts')
            ->modalWidth('md')
            ->modalSubmitActionLabel('Salvar')
            ->fillForm(fn (Post $record): array => [
                'series_slug' => $record->series_slug,
                'series_order' => $record->series_order,
            ])
            ->schema([
                TextInput::make('series_slug')
                    ->label('Slug da série')
                    ->placeholder('laravel-12-deep-dive')
                    ->maxLength(100)
                    ->helperText('Posts com o mesmo slug viram uma série. Deixe em branco se não pertence a nenhuma.'),
                TextInput::make('series_order')
                    ->label('Ordem na série')
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Posição deste post dentro da série (1, 2, 3...).'),
            ])
            ->action(function (array $data, Post $record): void {
                $record->update([
                    'series_slug' => $data['series_slug'] ?: null,
                    'series_order' => $data['series_order'] ?: null,
                ]);
            });
    }

    /**
     * Action de pré-visualização do post.
     *
     * Sempre disponível. Em rascunho, abre o preview (BlogController::show
     * libera draft pra usuário logado, com noindex); publicado, abre a página
     * pública. Abre em nova aba.
     */
    public static function preview(): Action
    {
        return Action::make('preview')
            ->label(fn (Post $record) => $record->status === 'published' ? 'Ver no site' : 'Pré-visualizar')
            ->icon(Heroicon::OutlinedEye)
            ->color('gray')
            ->url(fn (Post $record) => route('blog.show', $record), shouldOpenInNewTab: true);
    }

    /**
     * Action que importa o HTML completo do artigo.
     *
     * O conteúdo colado é sanitizado (HtmlImportService) e gravado direto em
     * body_html, SEM passar pelo RichEditor/TipTap — que reparseia o HTML e
     * descartaria o marcador <div data-chart> dos gráficos. A sanitização
     * remove script/iframe/canvas/on* e preserva o que o blog usa (texto,
     * tabelas, code blocks, links, imagens e o data-chart).
     *
     * Como o RichEditor já está montado com o valor antigo, recarregamos a
     * página após gravar pra ele refletir o conteúdo importado.
     */
    public static function importHtml(): Action
    {
        return Action::make('importHtml')
            ->label('Importar HTML')
            ->icon(Heroicon::OutlinedCodeBracket)
            ->color('gray')
            ->modalHeading('Importar HTML do artigo')
            ->modalDescription('Cole o HTML completo do artigo. Gráficos vão como <pre data-chart>{json}</pre>. Scripts e elementos perigosos são removidos automaticamente. Isso substitui o conteúdo atual do post.')
            ->modalWidth('3xl')
            ->modalSubmitActionLabel('Importar e substituir')
            ->schema([
                Textarea::make('html')
                    ->label('HTML')
                    ->required()
                    ->rows(16)
                    ->placeholder('<h2>Título</h2><p>Texto…</p><pre data-chart>{"type":"bar",…}</pre>')
                    ->helperText('O conteúdo passa por sanitização antes de ser salvo.'),
                Placeholder::make('chart_help')
                    ->label('Como inserir gráficos')
                    ->content(new HtmlString(self::chartHelpHtml())),
            ])
            ->action(function (array $data, Post $record, $livewire): void {
                $clean = app(HtmlImportService::class)->sanitize($data['html'] ?? '');

                if (trim($clean) === '') {
                    Notification::make()
                        ->title('Nada foi importado')
                        ->body('O HTML ficou vazio após a sanitização.')
                        ->warning()
                        ->send();

                    return;
                }

                $record->update(['body_html' => $clean]);

                Notification::make()
                    ->title('HTML importado')
                    ->body('O conteúdo foi sanitizado e salvo.')
                    ->success()
                    ->send();

                // Recarrega a página de edição para o RichEditor remontar com o
                // body_html importado. Sem isso o editor continuaria com o valor
                // antigo em memória e o próximo "Salvar" sobrescreveria o import.
                // Usa $livewire->redirect() (igual aos redirects das Pages do
                // projeto); o redirect() global solto não dispara no Livewire.
                $livewire->redirect(PostResource::getUrl('edit', ['record' => $record]));
            });
    }

    /**
     * HTML das instruções de gráfico, reaproveitado no modal de import e no
     * help abaixo do editor (PostForm). Mantém um único lugar pra documentar o
     * marcador <pre data-chart>. Estilos inline porque o CSS do Filament pode
     * purgar utilitários Tailwind soltos num HtmlString.
     */
    public static function chartHelpHtml(): string
    {
        return <<<'HTML'
<div style="font-size:.8125rem;line-height:1.5">
  <p style="margin:0 0 .5rem">Um gráfico é um bloco <code>&lt;pre data-chart&gt;</code> com um JSON dentro. Cole pelo botão <strong>Importar HTML</strong>. O servidor descreve o gráfico no <code>aria-label</code> (SEO) e o Chart.js o desenha no site.</p>
  <p style="margin:0 0 .25rem"><strong>Exemplo</strong> (barras empilhadas horizontais):</p>
  <pre style="background:rgba(0,0,0,.35);padding:.5rem .625rem;border-radius:.375rem;overflow:auto;white-space:pre-wrap;word-break:break-word;font-size:.6875rem;margin:0 0 .625rem">&lt;pre data-chart&gt;{"type":"bar","stacked":true,"horizontal":true,"labels":["Node","PHP","Java"],"datasets":[{"label":"Nativo","data":[67,22,41],"color":"#1D9E75"},{"label":"Manual","data":[0,48,197],"color":"#D85A30"}],"xLabel":"Linhas","title":"Verbosidade"}&lt;/pre&gt;</pre>
  <p style="margin:0 0 .25rem"><strong>Campos:</strong> <code>type</code> ("bar"), <code>labels</code> (categorias) e <code>datasets</code> (séries, cada uma com <code>label</code>, <code>data</code> numérico e <code>color</code> hex). Opcionais: <code>stacked</code>, <code>horizontal</code>, <code>xLabel</code>, <code>title</code>.</p>
  <ul style="margin:0;padding-left:1.1rem;list-style:disc">
    <li><code>data</code> alinha com <code>labels</code> por posição (mesma ordem e tamanho).</li>
    <li><code>color</code> só aceita hex (<code>#rgb</code> ou <code>#rrggbb</code>).</li>
    <li>A legenda aparece sozinha quando há 2 séries ou mais.</li>
    <li>JSON inválido: o gráfico é omitido e a página não quebra.</li>
  </ul>
</div>
HTML;
    }
}
