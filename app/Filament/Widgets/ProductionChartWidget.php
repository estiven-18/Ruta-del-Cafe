<?php

namespace App\Filament\Widgets;

use App\Models\Harvest;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class ProductionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Ingresos por Mes';

    protected ?string $description = 'Total de ingresos por ventas de café';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $monthlyData = Harvest::select(
                DB::raw("DATE_FORMAT(harvest_date, '%Y-%m') as month_key"),
                DB::raw("DATE_FORMAT(harvest_date, '%b %Y') as month_label"),
                DB::raw('SUM(total_income) as total')
            )
            ->whereNotNull('harvest_date')
            ->groupBy('month_key', 'month_label')
            ->orderBy('month_key')
            ->get();

        if ($monthlyData->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [0],
                        'backgroundColor' => ['#22C55E'],
                        'borderRadius' => 8,
                        'barPercentage' => 0.5,
                    ],
                ],
                'labels' => ['Sin datos'],
            ];
        }

        $labels = $monthlyData->pluck('month_label')->toArray();
        $values = $monthlyData->pluck('total')->map(fn ($v) => round($v, 2))->toArray();
        $maxVal = max($values);
        $colors = array_map(fn ($v) => $v == $maxVal ? '#15803d' : '#22C55E', $values);

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos (US$)',
                    'data' => $values,
                    'backgroundColor' => $colors,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                    'barPercentage' => 0.55,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => [
                    'backgroundColor' => '#1f2937',
                    'titleFont' => ['size' => 12, 'weight' => '500'],
                    'bodyFont' => ['size' => 12],
                    'padding' => 12,
                    'cornerRadius' => 8,
                    'displayColors' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => ['color' => '#f3f4f6', 'drawBorder' => false],
                    'ticks' => [
                        'font' => ['size' => 11],
                        'color' => '#9ca3af',
                    ],
                ],
                'x' => [
                    'grid' => ['display' => false],
                    'ticks' => ['font' => ['size' => 11], 'color' => '#6b7280'],
                ],
            ],
        ];
    }
}
