<?php

namespace App\Filament\Resources\CostCategories\Widgets;

use App\Models\CostCategory;
use App\Models\HarvestCost;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class CostCategoryStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();

        $totalCategories = CostCategory::count();
        $lastMonthCategories = CostCategory::where('created_at', '<', $startOfThisMonth)->count();
        $categoriesDiff = $totalCategories - $lastMonthCategories;

        $totalCostRecords = HarvestCost::count();
        $lastMonthRecords = HarvestCost::where('incurred_date', '<', $startOfThisMonth)->count();
        $recordsDiff = $totalCostRecords - $lastMonthRecords;

        $mostUsed = CostCategory::withCount('harvestCosts')
            ->orderByDesc('harvest_costs_count')
            ->first();

        return [
            Stat::make('Categorías de Costos', $totalCategories)
                ->description($categoriesDiff >= 0 ? "+{$categoriesDiff} este mes" : "{$categoriesDiff} este mes")
                ->descriptionIcon($categoriesDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($categoriesDiff >= 0 ? 'success' : 'danger')
                ->color('success'),
            Stat::make('Más Usada', $mostUsed?->name ?? 'N/A')
                ->description($mostUsed ? "{$mostUsed->harvest_costs_count} registros" : 'Sin datos')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->descriptionColor('success')
                ->color('primary'),
            Stat::make('Total Registros de Costos', $totalCostRecords)
                ->description($recordsDiff >= 0 ? "+{$recordsDiff} este mes" : "{$recordsDiff} este mes")
                ->descriptionIcon($recordsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($recordsDiff >= 0 ? 'danger' : 'success')
                ->color('warning'),
        ];
    }
}
