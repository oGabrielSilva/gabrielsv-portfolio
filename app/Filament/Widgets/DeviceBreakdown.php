<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DeviceBreakdown extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Dispositivo (30d)';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $since = now()->subDays(30);

        $rows = PageView::query()
            ->where('is_bot', false)
            ->where('viewed_at', '>=', $since)
            ->select('device', DB::raw('COUNT(*) as total'))
            ->groupBy('device')
            ->pluck('total', 'device');

        $labels = ['Desktop', 'Mobile', 'Tablet'];
        $data = [
            (int) ($rows['desktop'] ?? 0),
            (int) ($rows['mobile'] ?? 0),
            (int) ($rows['tablet'] ?? 0),
        ];

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => ['#00d1b2', '#3273dc', '#fbbf24'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
