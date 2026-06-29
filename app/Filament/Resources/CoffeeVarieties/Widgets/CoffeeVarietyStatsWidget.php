<?php

namespace App\Filament\Resources\CoffeeVarieties\Widgets;

use App\Models\CoffeeVariety;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class CoffeeVarietyStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();

        $totalVarieties = CoffeeVariety::count();
        $lastMonthVarieties = CoffeeVariety::where('created_at', '<', $startOfThisMonth)->count();
        $varietiesDiff = $totalVarieties - $lastMonthVarieties;

        $mostUsedVariety = CoffeeVariety::withCount('crops')
            ->orderByDesc('crops_count')
            ->first();

        $totalCrops = \App\Models\Crop::count();
        $lastMonthCrops = \App\Models\Crop::where('created_at', '<', $startOfThisMonth)->count();
        $cropsDiff = $totalCrops - $lastMonthCrops;

        return [
            Stat::make('Total Variedades', $totalVarieties)
                ->description($varietiesDiff >= 0 ? "+{$varietiesDiff} este mes" : "{$varietiesDiff} este mes")
                ->descriptionIcon($varietiesDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($varietiesDiff >= 0 ? 'success' : 'danger')
                ->color('primary'),
            Stat::make('Variedad Más Usada', $mostUsedVariety?->name ?? 'N/A')
                ->description($mostUsedVariety ? $mostUsedVariety->crops_count . ' cultivos' : 'Sin datos')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->descriptionColor('success')
                ->color('success'),
            Stat::make('Total Cultivos', $totalCrops)
                ->description($cropsDiff >= 0 ? "+{$cropsDiff} este mes" : "{$cropsDiff} este mes")
                ->descriptionIcon($cropsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($cropsDiff >= 0 ? 'success' : 'danger')
                ->color('warning'),
        ];
    }
}
