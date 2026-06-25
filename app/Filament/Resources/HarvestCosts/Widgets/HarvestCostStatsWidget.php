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
        $totalCosts = HarvestCost::sum('amount');

        $thisMonthCosts = HarvestCost::whereMonth('incurred_date', Carbon::now()->month)
            ->whereYear('incurred_date', Carbon::now()->year)
            ->sum('amount');

        $mostUsedCategory = HarvestCost::select('cost_category_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('cost_category_id')
            ->orderByDesc('count')
            ->first();

        $categoryName = $mostUsedCategory
            ? \App\Models\CostCategory::withTrashed()->find($mostUsedCategory->cost_category_id)?->name ?? 'N/A'
            : 'N/A';

        $totalRecords = HarvestCost::count();

        return [
            Stat::make('Total Costos', '$' . number_format($totalCosts, 0, ',', '.'))
                ->description('Costos registrados')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Este Mes', '$' . number_format($thisMonthCosts, 0, ',', '.'))
                ->description('Costos del mes actual')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
            Stat::make('Categoría Más Usada', $categoryName)
                ->description($mostUsedCategory?->count . ' registros' ?? 'Sin datos')
                ->descriptionIcon('heroicon-o-tag')
                ->color('warning'),
            Stat::make('Registros de Costos', $totalRecords)
                ->description('Total de costos')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('success'),
        ];
    }
}
