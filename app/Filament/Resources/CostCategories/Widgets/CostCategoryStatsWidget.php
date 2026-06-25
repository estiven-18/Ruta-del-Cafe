<?php

namespace App\Filament\Resources\CostCategories\Widgets;

use App\Models\CostCategory;
use App\Models\HarvestCost;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CostCategoryStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalCategories = CostCategory::count();

        $totalCostRecords = HarvestCost::count();

        $mostUsed = CostCategory::withCount('harvestCosts')
            ->orderByDesc('harvest_costs_count')
            ->first();

        return [
            Stat::make('Categorías de Costos', $totalCategories)
                ->description('Total registradas')
                ->descriptionIcon('heroicon-m-tag')
                ->color('success'),
            Stat::make('Más Usada', $mostUsed?->name ?? 'N/A')
                ->description($mostUsed ? "{$mostUsed->harvest_costs_count} registros" : 'Sin datos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Registros de Costos', $totalCostRecords)
                ->description('Costos registrados')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
