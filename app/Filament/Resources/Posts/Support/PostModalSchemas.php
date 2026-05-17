<?php

namespace App\Filament\Resources\Posts\Support;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                'published_at' => $record->published_at,
                'author_id' => $record->author_id,
            ])
            ->schema([
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Aparece em /b/{slug}.')
                    ->unique(table: 'posts', column: 'slug', ignorable: fn (Post $record) => $record),
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
     * Action que abre modal de Capa (cover image).
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
                    ->label('')
                    ->collection('cover')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios(['16:9', '4:3', '1:1']),
            ])
            ->fillForm(fn (Post $record): array => [
                'cover' => $record->getFirstMedia('cover')?->uuid,
            ])
            ->action(function (array $data, Post $record): void {
                // Spatie já lida com o upload via livewire; só forçamos um save.
                $record->save();
            });
    }

    /**
     * Action que abre modal de Taxonomia (categorias e tags).
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
                'categories' => $record->categories->pluck('id')->toArray(),
                'tags' => $record->tags->pluck('id')->toArray(),
            ])
            ->schema([
                Select::make('categories')
                    ->label('Categorias')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->required()->unique(table: 'categories', column: 'slug'),
                    ]),
                Select::make('tags')
                    ->label('Tags')
                    ->multiple()
                    ->relationship('tags', 'name')
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->required()->unique(table: 'tags', column: 'slug'),
                    ]),
            ])
            ->action(function (array $data, Post $record): void {
                $record->categories()->sync($data['categories'] ?? []);
                $record->tags()->sync($data['tags'] ?? []);
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
}
