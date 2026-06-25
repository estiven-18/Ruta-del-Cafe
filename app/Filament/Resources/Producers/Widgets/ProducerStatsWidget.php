<?php

namespace App\Filament\Resources\Producers\Widgets;

use App\Models\Farm;
use App\Models\Producer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProducerStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalProducers = Producer::count();

        $activeProducers = Producer::whereDoesntHave('farms', function ($query) {
            $query->where('farms.deleted_at', '!=', null);
        })->count();

        if ($activeProducers === 0) {
            $activeProducers = $totalProducers;
        }

        $totalFarms = Farm::count();

        $avgFarms = $totalProducers > 0 ? round($totalFarms / $totalProducers, 1) : 0;

        return [
            Stat::make('Total Productores', $totalProducers)
                ->description('Productores registrados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Productores Activos', $activeProducers)
                ->description('Con fincas asociadas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Total Fincas', $totalFarms)
                ->description('Fincas en el sistema')
                ->descriptionIcon('heroicon-m-home')
                ->color('success'),
            Stat::make('Promedio Fincas/Productor', $avgFarms)
                ->description('Fincas por productor')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('success'),
        ];
    }
}
