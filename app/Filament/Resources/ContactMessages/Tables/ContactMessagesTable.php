<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('read_at')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-s-envelope')
                    ->trueColor('gray')
                    ->falseColor('danger')
                    ->getStateUsing(fn (ContactMessage $record): bool => $record->read_at !== null),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight(fn (ContactMessage $record): string => $record->read_at === null ? 'bold' : 'normal')
                    ->limit(30),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('E-mail copiado!')
                    ->limit(35),
                TextColumn::make('subject')
                    ->label('Assunto')
                    ->searchable()
                    ->weight(fn (ContactMessage $record): string => $record->read_at === null ? 'bold' : 'normal')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Recebido em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Status')
                    ->placeholder('Todas')
                    ->trueLabel('Lidas')
                    ->falseLabel('Não lidas')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('read_at'),
                        false: fn ($query) => $query->whereNull('read_at'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
