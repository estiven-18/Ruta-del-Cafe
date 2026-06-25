<?php

namespace App\Filament\Resources\ProfitabilityReports\Widgets;

use App\Models\ProfitabilityReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfitabilityReportStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalRevenue = ProfitabilityReport::sum('total_income');
        $totalCosts = ProfitabilityReport::sum('total_costs');
        $netProfit = ProfitabilityReport::sum('net_profit');
        $avgMargin = ProfitabilityReport::avg('profitability_percentage') ?? 0;

        return [
            Stat::make('Total Ingresos', '$' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Ingresos totales')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Total Costos', '$' . number_format($totalCosts, 0, ',', '.'))
                ->description('Costos totales')
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('success'),
            Stat::make('Ganancia Neta', '$' . number_format($netProfit, 0, ',', '.'))
                ->description('Utilidad neta')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color($netProfit >= 0 ? 'success' : 'danger'),
            Stat::make('Margen Promedio', number_format($avgMargin, 1) . '%')
                ->description('Porcentaje promedio')
                ->descriptionIcon('heroicon-o-chart-pie')
                ->color($avgMargin >= 20 ? 'success' : ($avgMargin >= 0 ? 'warning' : 'danger')),
        ];
    }
}
