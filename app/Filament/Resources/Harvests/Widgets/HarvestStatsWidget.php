<?php

namespace App\Filament\Resources\Harvests\Widgets;

use App\Models\Harvest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class HarvestStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();

        $totalHarvests = Harvest::count();
        $thisMonthHarvests = Harvest::where('harvest_date', '>=', $startOfThisMonth)->count();
        $lastMonthHarvests = Harvest::where('harvest_date', '>=', $now->copy()->subMonth()->startOfMonth())
            ->where('harvest_date', '<', $startOfThisMonth)->count();
        $harvestsDiff = $thisMonthHarvests - $lastMonthHarvests;

        $totalProduction = Harvest::sum('net_weight_kg');
        $lastMonthProduction = Harvest::where('harvest_date', '<', $startOfThisMonth)->sum('net_weight_kg');
        $productionDiff = $totalProduction - $lastMonthProduction;

        $totalRevenue = Harvest::sum('total_income');
        $lastMonthRevenue = Harvest::where('harvest_date', '<', $startOfThisMonth)->sum('total_income');
        $revenueDiff = $totalRevenue - $lastMonthRevenue;

        $avgProduction = Harvest::avg('net_weight_kg') ?? 0;

        return [
            Stat::make('Total Cosechas', $totalHarvests)
                ->description($harvestsDiff >= 0 ? "+{$harvestsDiff} este mes" : "{$harvestsDiff} este mes")
                ->descriptionIcon($harvestsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($harvestsDiff >= 0 ? 'success' : 'danger')
                ->color('primary'),
            Stat::make('Producción Total', number_format($totalProduction, 0, ',', '.') . ' kg')
                ->description($productionDiff >= 0 ? '+'.number_format($productionDiff, 0).' kg' : number_format($productionDiff, 0).' kg')
                ->descriptionIcon($productionDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($productionDiff >= 0 ? 'success' : 'danger')
                ->color('info'),
            Stat::make('Ingresos Totales', '$' . number_format($totalRevenue, 0, ',', '.'))
                ->description($revenueDiff >= 0 ? '+$'.number_format($revenueDiff, 0) : '-$'.number_format(abs($revenueDiff), 0))
                ->descriptionIcon($revenueDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($revenueDiff >= 0 ? 'success' : 'danger')
                ->color('success'),
            Stat::make('Producción Promedio', number_format($avgProduction, 0) . ' kg')
                ->description('Por cosecha')
                ->descriptionIcon('heroicon-o-calculator')
                ->descriptionColor('gray')
                ->color('warning'),
        ];
    }
}
