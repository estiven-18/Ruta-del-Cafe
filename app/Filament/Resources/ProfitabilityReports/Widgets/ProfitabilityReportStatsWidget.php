<?php

namespace App\Filament\Resources\ProfitabilityReports\Widgets;

use App\Models\ProfitabilityReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ProfitabilityReportStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();

        $totalRevenue = ProfitabilityReport::sum('total_income');
        $lastMonthRevenue = ProfitabilityReport::where('created_at', '<', $startOfThisMonth)->sum('total_income');
        $revenueDiff = $totalRevenue - $lastMonthRevenue;

        $totalCosts = ProfitabilityReport::sum('total_costs');
        $lastMonthCosts = ProfitabilityReport::where('created_at', '<', $startOfThisMonth)->sum('total_costs');
        $costsDiff = $totalCosts - $lastMonthCosts;

        $netProfit = ProfitabilityReport::sum('net_profit');
        $lastMonthProfit = ProfitabilityReport::where('created_at', '<', $startOfThisMonth)->sum('net_profit');
        $profitDiff = $netProfit - $lastMonthProfit;

        $avgMargin = ProfitabilityReport::avg('profitability_percentage') ?? 0;
        $lastMonthMargin = ProfitabilityReport::where('created_at', '<', $startOfThisMonth)->avg('profitability_percentage') ?? 0;
        $marginDiff = $avgMargin - $lastMonthMargin;

        return [
            Stat::make('Total Ingresos', '$' . number_format($totalRevenue, 0, ',', '.'))
                ->description($revenueDiff >= 0 ? '+$'.number_format($revenueDiff, 0).' este mes' : '-$'.number_format(abs($revenueDiff), 0).' este mes')
                ->descriptionIcon($revenueDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($revenueDiff >= 0 ? 'success' : 'danger')
                ->color('success'),
            Stat::make('Total Costos', '$' . number_format($totalCosts, 0, ',', '.'))
                ->description($costsDiff >= 0 ? '+$'.number_format($costsDiff, 0).' este mes' : '-$'.number_format(abs($costsDiff), 0).' este mes')
                ->descriptionIcon($costsDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($costsDiff >= 0 ? 'danger' : 'success')
                ->color('danger'),
            Stat::make('Ganancia Neta', '$' . number_format($netProfit, 0, ',', '.'))
                ->description($profitDiff >= 0 ? '+$'.number_format($profitDiff, 0).' este mes' : '-$'.number_format(abs($profitDiff), 0).' este mes')
                ->descriptionIcon($profitDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($profitDiff >= 0 ? 'success' : 'danger')
                ->color($netProfit >= 0 ? 'success' : 'danger'),
            Stat::make('Margen Promedio', number_format($avgMargin, 1) . '%')
                ->description($marginDiff >= 0 ? '+'.number_format($marginDiff, 1).'%' : number_format($marginDiff, 1).'% este mes')
                ->descriptionIcon($marginDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($marginDiff >= 0 ? 'success' : 'danger')
                ->color($avgMargin >= 20 ? 'success' : ($avgMargin >= 0 ? 'warning' : 'danger')),
        ];
    }
}
