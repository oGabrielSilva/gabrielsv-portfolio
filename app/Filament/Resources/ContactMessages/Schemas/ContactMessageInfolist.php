<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Remetente')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name')
                            ->label('Nome'),
                        TextEntry::make('email')
                            ->label('E-mail')
                            ->copyable()
                            ->copyMessage('E-mail copiado!')
                            ->url(fn ($record): string => 'mailto:'.$record->email
                                .'?subject='.rawurlencode('Re: '.$record->subject)),
                        TextEntry::make('subject')
                            ->label('Assunto')
                            ->columnSpanFull(),
                    ]),

                Section::make('Mensagem')
                    ->components([
                        TextEntry::make('message')
                            ->label('')
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                Section::make('Metadados')
                    ->collapsed()
                    ->columns(2)
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Recebido em')
                            ->dateTime('d/m/Y \à\s H:i'),
                        TextEntry::make('read_at')
                            ->label('Lida em')
                            ->dateTime('d/m/Y \à\s H:i')
                            ->placeholder('Ainda não lida'),
                        TextEntry::make('ip')
                            ->label('IP')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('user_agent')
                            ->label('User-Agent')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
