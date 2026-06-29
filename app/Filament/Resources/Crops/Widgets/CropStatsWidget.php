<?php

namespace App\Filament\Resources\Crops\Widgets;

use App\Models\Crop;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class CropStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();

        $totalCrops = Crop::count();
        $lastMonthCrops = Crop::where('created_at', '<', $startOfThisMonth)->count();
        $cropsDiff = $totalCrops - $lastMonthCrops;

        $activeCrops = Crop::where('status', 'activo')->count();
        $lastMonthActive = Crop::where('status', 'activo')
            ->where('created_at', '<', $startOfThisMonth)->count();
        $activeDiff = $activeCrops - $lastMonthActive;

        $totalArea = Crop::sum('area_hectares');
        $lastMonthArea = Crop::where('created_at', '<', $startOfThisMonth)->sum('area_hectares');
        $areaDiff = $totalArea - $lastMonthArea;

        $avgArea = Crop::avg('area_hectares') ?? 0;

        return [
            Stat::make('Total Cultivos', $totalCrops)
                ->description($cropsDiff >= 0 ? "+{$cropsDiff} este mes" : "{$cropsDiff} este mes")
                ->descriptionIcon($cropsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($cropsDiff >= 0 ? 'success' : 'danger')
                ->color('primary'),
            Stat::make('Cultivos Activos', $activeCrops)
                ->description($activeDiff >= 0 ? "+{$activeDiff} este mes" : "{$activeDiff} este mes")
                ->descriptionIcon($activeDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($activeDiff >= 0 ? 'success' : 'danger')
                ->color('success'),
            Stat::make('Área Total Cultivada', number_format($totalArea, 2) . ' ha')
                ->description($areaDiff >= 0 ? '+'.number_format($areaDiff, 2).' ha' : number_format($areaDiff, 2).' ha')
                ->descriptionIcon($areaDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($areaDiff >= 0 ? 'success' : 'danger')
                ->color('info'),
            Stat::make('Área Promedio', number_format($avgArea, 2) . ' ha')
                ->description('Por cultivo')
                ->descriptionIcon('heroicon-o-calculator')
                ->descriptionColor('gray')
                ->color('warning'),
        ];
    }
}
