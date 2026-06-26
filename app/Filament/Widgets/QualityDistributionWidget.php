<?php

namespace App\Filament\Widgets;

use App\Models\QualityEvaluation;
use Filament\Widgets\ChartWidget;

class QualityDistributionWidget extends ChartWidget
{
    protected ?string $heading = 'Distribución de Calidad';

    protected ?string $description = 'Evaluaciones de calidad del café por categoría';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '360px';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $specialty = QualityEvaluation::where('quality_grade', 'especial')->count();
        $premium = QualityEvaluation::where('quality_grade', 'alto')->count();
        $commercial = QualityEvaluation::where('quality_grade', 'medio')->count();
        $belowGrade = QualityEvaluation::where('quality_grade', 'bajo')->count();

        $total = $specialty + $premium + $commercial + $belowGrade;

        if ($total === 0) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#e5e7eb'],
                        'borderWidth' => 0,
                    ],
                ],
                'labels' => ['Sin datos'],
            ];
        }

        return [
            'datasets' => [
                [
                    'data' => [$specialty, $premium, $commercial, $belowGrade],
                    'backgroundColor' => ['#8b5cf6', '#22c55e', '#f59e0b', '#ef4444'],
                    'hoverBackgroundColor' => ['#7c3aed', '#16a34a', '#d97706', '#dc2626'],
                    'borderWidth' => 0,
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => ['Especialidad', 'Premium', 'Comercial', 'Bajo Grado'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '65%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'padding' => 20,
                        'font' => ['size' => 12],
                        'color' => '#374151',
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => '#1f2937',
                    'titleFont' => ['size' => 12, 'weight' => '500'],
                    'bodyFont' => ['size' => 12],
                    'padding' => 12,
                    'cornerRadius' => 8,
                ],
            ],
        ];
    }
}
