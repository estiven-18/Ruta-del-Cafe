<?php

namespace App\Filament\Widgets;

use App\Models\Farm;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class ProductionByFarmWidget extends ChartWidget
{
    protected ?string $heading = 'Producción por Finca';

    protected ?string $description = 'Ingresos totales por finca';

    protected static ?int $sort = 5;

    protected ?string $maxHeight = '280px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $farms = Farm::withTrashed()
            ->join('crops', 'crops.farm_id', '=', 'farms.id')
            ->join('harvests', 'harvests.crop_id', '=', 'crops.id')
            ->select('farms.id', 'farms.name')
            ->selectRaw('SUM(harvests.total_income) as total_production')
            ->groupBy('farms.id', 'farms.name')
            ->orderByDesc('total_production')
            ->limit(6)
            ->get();

        if ($farms->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [0],
                        'backgroundColor' => ['#e5e7eb'],
                        'borderRadius' => 6,
                        'barPercentage' => 0.5,
                    ],
                ],
                'labels' => ['Sin datos'],
            ];
        }

        $maxVal = $farms->max('total_production');

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos (US$)',
                    'data' => $farms->pluck('total_production')->map(fn ($v) => round($v, 2))->toArray(),
                    'backgroundColor' => $farms->map(fn ($f) => $f->total_production == $maxVal ? '#15803d' : '#22C55E')->toArray(),
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                    'barPercentage' => 0.55,
                    'maxBarThickness' => 40,
                ],
            ],
            'labels' => $farms->pluck('name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
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
                'x' => [
                    'beginAtZero' => true,
                    'grid' => ['color' => '#f3f4f6', 'drawBorder' => false],
                    'ticks' => [
                        'font' => ['size' => 11],
                        'color' => '#9ca3af',
                    ],
                ],
                'y' => [
                    'grid' => ['display' => false],
                    'ticks' => [
                        'font' => ['size' => 12, 'weight' => '500'],
                        'color' => '#374151',
                    ],
                ],
            ],
        ];
    }
}
