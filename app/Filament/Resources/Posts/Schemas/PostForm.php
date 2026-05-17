<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('')
                    ->placeholder('Título do post')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set, callable $get) {
                        if ($operation === 'create' && empty($get('slug'))) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->extraAttributes([
                        'class' => 'fi-post-title-input',
                    ])
                    ->columnSpanFull(),

                RichEditor::make('body_html')
                    ->label('')
                    ->placeholder('Comece a escrever...')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('posts/attachments')
                    ->fileAttachmentsVisibility('public')
                    ->extraAttributes(['class' => 'fi-post-editor'])
                    ->columnSpanFull(),
            ]);
    }
}
