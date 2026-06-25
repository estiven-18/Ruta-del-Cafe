<?php

namespace App\Filament\Resources\CoffeeVarieties\Widgets;

use App\Models\CoffeeVariety;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CoffeeVarietyStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalVarieties = CoffeeVariety::count();

        $mostUsedVariety = CoffeeVariety::withCount('crops')
            ->orderByDesc('crops_count')
            ->first();

        $totalCrops = \App\Models\Crop::count();

        return [
            Stat::make('Total Variedades', $totalVarieties)
                ->description('Categorías registradas')
                ->descriptionIcon('heroicon-o-sparkles')
                ->color('primary'),
            Stat::make('Variedad Más Usada', $mostUsedVariety?->name ?? 'N/A')
                ->description($mostUsedVariety ? $mostUsedVariety->crops_count . ' cultivos' : 'Sin datos')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Cultivos', $totalCrops)
                ->description('Asociados a variedades')
                ->descriptionIcon('heroicon-o-map')
                ->color('warning'),
        ];
    }
}
