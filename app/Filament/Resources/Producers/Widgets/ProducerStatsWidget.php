<?php

namespace App\Filament\Resources\Producers\Widgets;

use App\Models\Farm;
use App\Models\Producer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ProducerStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();

        $totalProducers = Producer::count();
        $lastMonthProducers = Producer::where('created_at', '<', $startOfThisMonth)->count();
        $producersDiff = $totalProducers - $lastMonthProducers;

        $activeProducers = Producer::whereDoesntHave('farms', function ($query) {
            $query->where('farms.deleted_at', '!=', null);
        })->count();

        if ($activeProducers === 0) {
            $activeProducers = $totalProducers;
        }

        $totalFarms = Farm::count();
        $lastMonthFarms = Farm::where('created_at', '<', $startOfThisMonth)->count();
        $farmsDiff = $totalFarms - $lastMonthFarms;

        $avgFarms = $totalProducers > 0 ? round($totalFarms / $totalProducers, 1) : 0;

        return [
            Stat::make('Total Productores', $totalProducers)
                ->description($producersDiff >= 0 ? "+{$producersDiff} este mes" : "{$producersDiff} este mes")
                ->descriptionIcon($producersDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($producersDiff >= 0 ? 'success' : 'danger')
                ->color('success'),
            Stat::make('Productores Activos', $activeProducers)
                ->description('Con fincas asociadas')
                ->descriptionIcon('heroicon-o-check-circle')
                ->descriptionColor('success')
                ->color('success'),
            Stat::make('Total Fincas', $totalFarms)
                ->description($farmsDiff >= 0 ? "+{$farmsDiff} este mes" : "{$farmsDiff} este mes")
                ->descriptionIcon($farmsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($farmsDiff >= 0 ? 'success' : 'danger')
                ->color('primary'),
            Stat::make('Promedio Fincas/Productor', $avgFarms)
                ->description('Fincas por productor')
                ->descriptionIcon('heroicon-o-calculator')
                ->descriptionColor('gray')
                ->color('warning'),
        ];
    }
}
