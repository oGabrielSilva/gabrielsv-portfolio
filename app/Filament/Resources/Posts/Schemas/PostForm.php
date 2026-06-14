<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Resources\Posts\Support\PostModalSchemas;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Ações de conteúdo acima do título (preview + importar HTML).
                // Só fazem sentido num post já existente; no create ficam ocultas.
                Actions::make([
                    PostModalSchemas::preview(),
                    PostModalSchemas::importHtml(),
                ])
                    ->visible(fn (?\App\Models\Post $record): bool => $record !== null)
                    ->columnSpanFull(),

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

                // Ajuda de gráficos abaixo do editor (mesmo conteúdo do modal de
                // import). Colapsada por padrão pra não competir com o editor.
                Section::make('Como inserir gráficos')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('chart_help')
                            ->hiddenLabel()
                            ->content(new HtmlString(PostModalSchemas::chartHelpHtml())),
                    ]),
            ]);
    }
}
