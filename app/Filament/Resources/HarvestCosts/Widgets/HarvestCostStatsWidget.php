<?php

namespace App\Filament\Resources\HarvestCosts\Widgets;

use App\Models\HarvestCost;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class HarvestCostStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();

        $totalCosts = HarvestCost::sum('amount');
        $lastMonthTotalCosts = HarvestCost::where('incurred_date', '<', $startOfThisMonth)->sum('amount');
        $costsDiff = $totalCosts - $lastMonthTotalCosts;

        $thisMonthCosts = HarvestCost::where('incurred_date', '>=', $startOfThisMonth)->sum('amount');
        $previousMonthCosts = HarvestCost::where('incurred_date', '>=', $startOfLastMonth)
            ->where('incurred_date', '<', $startOfThisMonth)->sum('amount');
        $monthlyDiff = $thisMonthCosts - $previousMonthCosts;

        $mostUsedCategory = HarvestCost::select('cost_category_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('cost_category_id')
            ->orderByDesc('count')
            ->first();

        $categoryName = $mostUsedCategory
            ? \App\Models\CostCategory::withTrashed()->find($mostUsedCategory->cost_category_id)?->name ?? 'N/A'
            : 'N/A';

        $totalRecords = HarvestCost::count();
        $lastMonthRecords = HarvestCost::where('incurred_date', '<', $startOfThisMonth)->count();
        $recordsDiff = $totalRecords - $lastMonthRecords;

        return [
            Stat::make('Total Costos', '$' . number_format($totalCosts, 0, ',', '.'))
                ->description($costsDiff >= 0 ? '+$'.number_format($costsDiff, 0).' acumulado' : '-$'.number_format(abs($costsDiff), 0).' acumulado')
                ->descriptionIcon($costsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($costsDiff >= 0 ? 'danger' : 'success')
                ->color('danger'),
            Stat::make('Este Mes', '$' . number_format($thisMonthCosts, 0, ',', '.'))
                ->description($monthlyDiff >= 0 ? '+$'.number_format($monthlyDiff, 0).' vs mes anterior' : '-$'.number_format(abs($monthlyDiff), 0).' vs mes anterior')
                ->descriptionIcon($monthlyDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($monthlyDiff >= 0 ? 'danger' : 'success')
                ->color('warning'),
            Stat::make('Categoría Más Usada', $categoryName)
                ->description($mostUsedCategory?->count . ' registros' ?? 'Sin datos')
                ->descriptionIcon('heroicon-o-tag')
                ->descriptionColor('gray')
                ->color('info'),
            Stat::make('Registros de Costos', $totalRecords)
                ->description($recordsDiff >= 0 ? "+{$recordsDiff} este mes" : "{$recordsDiff} este mes")
                ->descriptionIcon($recordsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($recordsDiff >= 0 ? 'danger' : 'success')
                ->color('primary'),
        ];
    }
}
