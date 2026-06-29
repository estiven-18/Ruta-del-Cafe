<?php

namespace App\Filament\Resources\QualityEvaluations\Widgets;

use App\Models\QualityEvaluation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class QualityEvaluationStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();

        $averageScore = QualityEvaluation::avg('final_score') ?? 0;
        $lastMonthAvg = QualityEvaluation::where('evaluation_date', '<', $startOfThisMonth)->avg('final_score') ?? 0;
        $scoreDiff = $averageScore - $lastMonthAvg;

        $specialtyCount = QualityEvaluation::where('quality_grade', 'especial')->count();
        $thisMonthSpecialty = QualityEvaluation::where('quality_grade', 'especial')
            ->where('evaluation_date', '>=', $startOfThisMonth)->count();

        $premiumCount = QualityEvaluation::where('quality_grade', 'alto')->count();
        $thisMonthPremium = QualityEvaluation::where('quality_grade', 'alto')
            ->where('evaluation_date', '>=', $startOfThisMonth)->count();

        $commercialCount = QualityEvaluation::where('quality_grade', 'medio')->count();
        $thisMonthCommercial = QualityEvaluation::where('quality_grade', 'medio')
            ->where('evaluation_date', '>=', $startOfThisMonth)->count();

        $belowCount = QualityEvaluation::where('quality_grade', 'bajo')->count();
        $thisMonthBelow = QualityEvaluation::where('quality_grade', 'bajo')
            ->where('evaluation_date', '>=', $startOfThisMonth)->count();

        return [
            Stat::make('Puntaje Promedio', number_format($averageScore, 1))
                ->description($scoreDiff >= 0 ? '+'.number_format($scoreDiff, 1).' este mes' : number_format($scoreDiff, 1).' este mes')
                ->descriptionIcon($scoreDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($scoreDiff >= 0 ? 'success' : 'danger')
                ->color('primary'),
            Stat::make('Cafés Especiales', $specialtyCount)
                ->description($thisMonthSpecialty > 0 ? "+{$thisMonthSpecialty} este mes" : 'Sin nuevos este mes')
                ->descriptionIcon($thisMonthSpecialty > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-minus-circle')
                ->descriptionColor($thisMonthSpecialty > 0 ? 'success' : 'gray')
                ->color('purple'),
            Stat::make('Cafés Premium', $premiumCount)
                ->description($thisMonthPremium > 0 ? "+{$thisMonthPremium} este mes" : 'Sin nuevos este mes')
                ->descriptionIcon($thisMonthPremium > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-minus-circle')
                ->descriptionColor($thisMonthPremium > 0 ? 'success' : 'gray')
                ->color('success'),
            Stat::make('Cafés Comerciales', $commercialCount)
                ->description($thisMonthCommercial > 0 ? "+{$thisMonthCommercial} este mes" : 'Sin nuevos este mes')
                ->descriptionIcon($thisMonthCommercial > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-minus-circle')
                ->descriptionColor($thisMonthCommercial > 0 ? 'warning' : 'gray')
                ->color('warning'),
        ];
    }
}
