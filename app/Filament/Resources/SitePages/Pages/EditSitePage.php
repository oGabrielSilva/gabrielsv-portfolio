<?php

namespace App\Filament\Resources\SitePages\Pages;

use App\Filament\Resources\SitePages\SitePageResource;
use App\Models\SitePage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditSitePage extends EditRecord
{
    protected static string $resource = SitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importHtml')
                ->label('Importar HTML')
                ->icon(Heroicon::OutlinedCodeBracket)
                ->modalHeading('Importar HTML cru')
                ->modalDescription('Cole o HTML completo (ex: gerado pelo Claude Web). Substitui o conteúdo atual do editor.')
                ->modalWidth('3xl')
                ->modalSubmitActionLabel('Substituir conteúdo')
                ->schema([
                    Textarea::make('html')
                        ->label('HTML')
                        ->rows(20)
                        ->placeholder('<h2>Editor & terminal</h2>...')
                        ->required()
                        ->extraInputAttributes(['style' => 'font-family: ui-monospace, monospace; font-size: 0.85rem;']),
                ])
                ->action(function (array $data, SitePage $record): void {
                    $record->update(['body_html' => $data['html']]);

                    Notification::make()
                        ->title('HTML importado com sucesso')
                        ->success()
                        ->send();

                    $this->fillForm();
                }),

            DeleteAction::make(),
        ];
    }
}
