<?php

namespace App\Filament\Resources\SitePages\Schemas;

use App\Models\SitePage;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SitePageForm
{
    /**
     * Slugs reservados: têm rota nomeada hardcoded em routes/web.php.
     * Trocar quebra a URL e o link em todo o site.
     */
    private const RESERVED_SLUGS = ['sobre', 'uses', 'now'];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identidade')
                    ->components([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->disabled(fn (?SitePage $record) => $record && in_array($record->slug, self::RESERVED_SLUGS, true))
                            ->dehydrated()
                            ->helperText(fn (?SitePage $record) => $record && in_array($record->slug, self::RESERVED_SLUGS, true)
                                ? 'Slug reservado: a rota /'.$record->slug.' depende deste valor.'
                                : 'A URL fica /{slug}. Exemplo: "uses" → /uses.'),

                        TextInput::make('subtitle')
                            ->label('Subtítulo')
                            ->maxLength(255)
                            ->helperText('Aparece logo abaixo do título em texto cinza.'),
                    ]),

                Section::make('Conteúdo')
                    ->components([
                        RichEditor::make('body_html')
                            ->label('')
                            ->placeholder('Comece a escrever...')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('site-pages/attachments')
                            ->fileAttachmentsVisibility('public'),
                    ]),

                Section::make('SEO')
                    ->collapsed()
                    ->components([
                        Textarea::make('meta_description')
                            ->label('Meta description')
                            ->rows(2)
                            ->maxLength(160)
                            ->helperText('Até 160 caracteres. Em branco usa o subtítulo.'),
                    ]),
            ]);
    }
}
