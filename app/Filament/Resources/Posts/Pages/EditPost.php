<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Support\PostModalSchemas;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PostModalSchemas::publication(),
            PostModalSchemas::cover(),
            PostModalSchemas::taxonomy(),
            PostModalSchemas::series(),
            PostModalSchemas::seo(),
            Action::make('view')
                ->label('Ver no site')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn () => route('blog.show', $this->record), shouldOpenInNewTab: true)
                ->visible(fn () => $this->record->status === 'published'),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
