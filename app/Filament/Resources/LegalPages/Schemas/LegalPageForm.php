<?php

namespace App\Filament\Resources\LegalPages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LegalPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->components([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Usado em /legal/{slug}. Recomendados: privacidade, termos, cookies.'),
                        Textarea::make('meta_description')
                            ->label('Meta description')
                            ->rows(2)
                            ->maxLength(160)
                            ->helperText('Aparece no resultado do Google. Em branco usa o título.'),
                        RichEditor::make('body_html')
                            ->label('Conteúdo')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('legal/attachments'),
                    ]),
            ]);
    }
}
