<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TopReferrersTable extends TableWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Origem (referrers, 30d)';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        $since = now()->subDays(30);

        return $table
            ->query(
                fn (): Builder => PageView::query()
                    ->where('is_bot', false)
                    ->where('viewed_at', '>=', $since)
                    ->whereNotNull('referrer_host')
                    ->select('referrer_host', DB::raw('COUNT(*) as views_total'))
                    ->groupBy('referrer_host')
                    ->orderByDesc('views_total')
                    ->limit(10)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('referrer_host')
                    ->label('Domínio'),
                TextColumn::make('views_total')
                    ->label('Visitas')
                    ->numeric()
                    ->alignEnd(),
            ]);
    }

    public function getTableRecordKey(Model|array $record): string
    {
        return is_array($record) ? ($record['referrer_host'] ?? '') : (string) $record->referrer_host;
    }
}
