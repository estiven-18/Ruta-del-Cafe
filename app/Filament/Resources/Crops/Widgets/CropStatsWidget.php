<?php

namespace App\Filament\Resources\Crops\Widgets;

use App\Models\Crop;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CropStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalCrops = Crop::count();

        $activeCrops = Crop::where('status', 'activo')->count();

        $totalArea = Crop::sum('area_hectares');

        $avgArea = Crop::avg('area_hectares') ?? 0;

        return [
            Stat::make('Total Cultivos', $totalCrops)
                ->description('Cultivos registrados')
                ->descriptionIcon('heroicon-o-square-3-stack-3d')
                ->color('success'),
            Stat::make('Cultivos Activos', $activeCrops)
                ->description('En producción')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Área Total Cultivada', number_format($totalArea, 2) . ' ha')
                ->description('Hectáreas totales')
                ->descriptionIcon('heroicon-o-map')
                ->color('warning'),
            Stat::make('Área Promedio', number_format($avgArea, 2) . ' ha')
                ->description('Por cultivo')
                ->descriptionIcon('heroicon-o-map')
                ->color('warning'),
        ];
    }
}
