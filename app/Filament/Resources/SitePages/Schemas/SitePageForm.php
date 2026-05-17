<?php

namespace App\Filament\Resources\SitePages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SitePageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identidade')
                    ->columns(2)
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
                            ->helperText('A URL fica /{slug}. Exemplo: "uses" → /uses.'),

                        TextInput::make('subtitle')
                            ->label('Subtítulo')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Aparece logo abaixo do título em texto cinza.'),
                    ]),

                Section::make('Conteúdo')
                    ->components([
                        RichEditor::make('body_html')
                            ->label('')
                            ->placeholder('Comece a escrever...')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('site-pages/attachments')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
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
