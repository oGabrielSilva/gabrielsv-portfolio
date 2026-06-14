<?php

namespace App\Filament\Resources\Posts\Support;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;
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
}
