<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $now = now();
        $last30 = $now->copy()->subDays(30);
        $prev30 = $now->copy()->subDays(60);

        $current = PageView::where('is_bot', false)
            ->where('viewed_at', '>=', $last30)
            ->count();

        $previous = PageView::where('is_bot', false)
            ->whereBetween('viewed_at', [$prev30, $last30])
            ->count();

        $diff = $current - $previous;
        $description = $previous > 0
            ? sprintf('%s%% vs 30d anteriores', round(($diff / max($previous, 1)) * 100))
            : 'Sem dados anteriores';

        $bots = PageView::where('is_bot', true)
            ->where('viewed_at', '>=', $last30)
            ->count();

        $today = PageView::where('is_bot', false)
            ->whereDate('viewed_at', $now->toDateString())
            ->count();

        return [
            Stat::make('Visitas (30d)', number_format($current, 0, ',', '.'))
                ->description($description)
                ->descriptionIcon($diff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($diff >= 0 ? 'success' : 'danger'),
            Stat::make('Hoje', number_format($today, 0, ',', '.'))
                ->description('Pageviews humanos hoje')
                ->color('primary'),
            Stat::make('Bots (30d)', number_format($bots, 0, ',', '.'))
                ->description('Crawlers identificados')
                ->color('gray'),
        ];
    }
}
