<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identidade')
                    ->components([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->label('Avatar')
                            ->collection('avatar')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper(),

                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Textarea::make('bio')
                            ->label('Bio')
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText('Aparece no rodapé dos seus posts.'),
                    ]),

                Section::make('Acesso')
                    ->components([
                        TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->helperText('Deixe em branco para manter a senha atual.')
                            ->maxLength(255),

                        DateTimePicker::make('email_verified_at')
                            ->label('E-mail verificado em')
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                    ]),
            ]);
    }
}
