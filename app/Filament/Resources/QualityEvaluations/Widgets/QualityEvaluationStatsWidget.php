<?php

namespace App\Filament\Resources\QualityEvaluations\Widgets;

use App\Models\QualityEvaluation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QualityEvaluationStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $averageScore = QualityEvaluation::avg('final_score') ?? 0;

        $specialtyCount = QualityEvaluation::where('quality_grade', 'especial')->count();

        $premiumCount = QualityEvaluation::where('quality_grade', 'alto')->count();

        $commercialCount = QualityEvaluation::where('quality_grade', 'medio')->count();

        return [
            Stat::make('Puntaje Promedio', number_format($averageScore, 1))
                ->description('Promedio de calidades')
                ->descriptionIcon('heroicon-o-star')
                ->color('primary'),
            Stat::make('Cafés Comerciales', $commercialCount)
                ->description('Calidad comercial')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('warning'),
            Stat::make('Cafés Premium', $premiumCount)
                ->description('Calidad premium')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make('Cafés Especiales', $specialtyCount)
                ->description('Calidad especial')
                ->descriptionIcon('heroicon-o-fire')
                ->color('purple'),
        ];
    }
}
