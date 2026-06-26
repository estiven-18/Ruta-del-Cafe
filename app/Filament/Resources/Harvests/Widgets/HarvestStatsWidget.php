<?php

namespace App\Filament\Resources\Harvests\Widgets;

use App\Models\Harvest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HarvestStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalHarvests = Harvest::count();

        $totalProduction = Harvest::sum('net_weight_kg');

        $totalRevenue = Harvest::sum('total_income');

        $avgProduction = Harvest::avg('net_weight_kg') ?? 0;

        return [
            Stat::make('Total Cosechas', $totalHarvests)
                ->description('Registros de cosechas')
                ->descriptionIcon('heroicon-o-cube-transparent')
                ->color('success'),
            Stat::make('Producción Total', number_format($totalProduction, 0, ',', '.') . ' kg')
                ->description('Kilogramos cosechados')
                ->descriptionIcon('heroicon-o-scale')
                ->color('success'),
            Stat::make('Ingresos Totales', '$' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Ingresos por ventas')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Producción Promedio', number_format($avgProduction, 0) . ' kg')
                ->description('Por cosecha')
                ->descriptionIcon('heroicon-o-calculator')
                ->color('warning'),
        ];
    }
}
