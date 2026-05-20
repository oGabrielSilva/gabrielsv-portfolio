<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    /**
     * Marca a mensagem como lida automaticamente ao abrir o detalhe.
     */
    protected function resolveRecord(int|string $key): \Illuminate\Database\Eloquent\Model
    {
        /** @var ContactMessage $record */
        $record = parent::resolveRecord($key);
        $record->markAsRead();

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Responder')
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->color('primary')
                ->url(fn (ContactMessage $record): string => 'mailto:'.$record->email
                    .'?subject='.rawurlencode('Re: '.$record->subject))
                ->openUrlInNewTab(),

            Action::make('mark_unread')
                ->label('Marcar como não lida')
                ->icon(Heroicon::OutlinedEnvelope)
                ->color('gray')
                ->visible(fn (ContactMessage $record): bool => $record->read_at !== null)
                ->action(function (ContactMessage $record): void {
                    $record->markAsUnread();
                    $this->redirect(static::getResource()::getUrl('view', ['record' => $record]));
                }),

            Action::make('mark_read')
                ->label('Marcar como lida')
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->color('gray')
                ->visible(fn (ContactMessage $record): bool => $record->read_at === null)
                ->action(function (ContactMessage $record): void {
                    $record->markAsRead();
                    $this->redirect(static::getResource()::getUrl('view', ['record' => $record]));
                }),

            DeleteAction::make(),
        ];
    }
}
