<?php

namespace App\Filament\Resources\Farms\Widgets;

use App\Models\Crop;
use App\Models\Farm;
use App\Models\Harvest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class FarmStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();

        $totalArea = Farm::sum('total_area_hectares');
        $lastMonthArea = Farm::where('created_at', '<', $startOfThisMonth)->sum('total_area_hectares');
        $areaDiff = $totalArea - $lastMonthArea;

        $activeCrops = Crop::where('status', 'activo')->count();
        $lastMonthActive = Crop::where('status', 'activo')
            ->where('created_at', '<', $startOfThisMonth)->count();
        $cropsDiff = $activeCrops - $lastMonthActive;

        $totalHarvests = Harvest::count();
        $thisMonthHarvests = Harvest::where('harvest_date', '>=', $startOfThisMonth)->count();
        $lastMonthHarvests = Harvest::where('harvest_date', '>=', $now->copy()->subMonth()->startOfMonth())
            ->where('harvest_date', '<', $startOfThisMonth)->count();
        $harvestsDiff = $thisMonthHarvests - $lastMonthHarvests;

        return [
            Stat::make('Área Total', number_format($totalArea, 2) . ' ha')
                ->description($areaDiff >= 0 ? '+'.number_format($areaDiff, 2).' ha' : number_format($areaDiff, 2).' ha')
                ->descriptionIcon($areaDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($areaDiff >= 0 ? 'success' : 'danger')
                ->color('primary'),
            Stat::make('Cultivos Activos', $activeCrops)
                ->description($cropsDiff >= 0 ? "+{$cropsDiff} este mes" : "{$cropsDiff} este mes")
                ->descriptionIcon($cropsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($cropsDiff >= 0 ? 'success' : 'danger')
                ->color('success'),
            Stat::make('Total Cosechas', $totalHarvests)
                ->description($harvestsDiff >= 0 ? "+{$harvestsDiff} este mes" : "{$harvestsDiff} este mes")
                ->descriptionIcon($harvestsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($harvestsDiff >= 0 ? 'success' : 'danger')
                ->color('warning'),
        ];
    }
}
