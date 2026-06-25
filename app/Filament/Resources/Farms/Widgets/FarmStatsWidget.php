<?php

namespace App\Filament\Resources\Farms\Widgets;

use App\Models\Crop;
use App\Models\Farm;
use App\Models\Harvest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FarmStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalArea = Farm::sum('total_area_hectares');

        $activeCrops = Crop::where('status', 'activo')->count();

        $totalHarvests = Harvest::count();

        return [
            Stat::make('Área Total', number_format($totalArea, 2) . ' ha')
                ->description('Hectáreas registradas')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary'),
            Stat::make('Cultivos Activos', $activeCrops)
                ->description('En producción')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Cosechas', $totalHarvests)
                ->description('Cosechas registradas')
                ->descriptionIcon('heroicon-m-cube-transparent')
                ->color('warning'),
        ];
    }
}
