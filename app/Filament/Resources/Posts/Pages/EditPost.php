<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Support\PostModalSchemas;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        // preview() e importHtml() agora ficam no topo do form (PostForm),
        // acima do título — não no header, pra não apertar o cabeçalho.
        return [
            PostModalSchemas::publication(),
            PostModalSchemas::cover(),
            PostModalSchemas::taxonomy(),
            PostModalSchemas::series(),
            PostModalSchemas::seo(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
