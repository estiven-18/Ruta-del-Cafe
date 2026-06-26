<?php

namespace App\Filament\Widgets;

use App\Models\Harvest;
use App\Models\HarvestCost;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Harvest::sum('total_income');
        $totalCosts = HarvestCost::sum('amount');
        $netProfit = $totalRevenue - $totalCosts;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 0;

        $prevRevenue = Harvest::where('harvest_date', '>=', now()->subQuarter()->startOfQuarter())
            ->where('harvest_date', '<', now()->startOfQuarter())
            ->sum('total_income');
        $revenueGrowth = $prevRevenue > 0 ? round((($totalRevenue - $prevRevenue) / max($prevRevenue, 1)) * 100) : 0;

        $prevCosts = HarvestCost::where('incurred_date', '>=', now()->subQuarter()->startOfQuarter())
            ->where('incurred_date', '<', now()->startOfQuarter())
            ->sum('amount');
        $costGrowth = $prevCosts > 0 ? round((($totalCosts - $prevCosts) / max($prevCosts, 1)) * 100) : 0;

        $prevProfit = $prevRevenue - $prevCosts;
        $profitGrowth = $prevProfit > 0 ? round((($netProfit - $prevProfit) / max($prevProfit, 1)) * 100) : 0;

        $prevMargin = $prevRevenue > 0 ? round(($prevProfit / $prevRevenue) * 100, 1) : 0;
        $marginDiff = round($profitMargin - $prevMargin, 1);

        // INGRESOS: sube = verde, baja = rojo
        $revenueColor = $revenueGrowth >= 0 ? 'success' : 'danger';

        // COSTOS: siempre rojo (es gasto)
        $costColor = 'danger';

        // GANANCIA: sube = verde, baja = rojo
        $profitColor = $profitGrowth >= 0 ? 'success' : 'danger';

        // MARGEN: >=30% = verde, <30% = rojo
        $marginColor = $profitMargin >= 30 ? 'success' : 'danger';

        return [
            Stat::make('Ingresos Totales', '$' . number_format($totalRevenue, 0, ',', '.'))
                ->description("{$revenueGrowth}% este trimestre")
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->descriptionColor($revenueColor)
                ->color($revenueColor)
                ->chart($this->getRevenueSparkData()),

            Stat::make('Costos Totales', '$' . number_format($totalCosts, 0, ',', '.'))
                ->description("{$costGrowth}% este trimestre")
                ->descriptionIcon($costGrowth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->descriptionColor($costColor)
                ->color($costColor)
                ->chart($this->getCostSparkData()),

            Stat::make('Ganancia Neta', '$' . number_format($netProfit, 0, ',', '.'))
                ->description("{$profitGrowth}% este trimestre")
                ->descriptionIcon($profitGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->descriptionColor($profitColor)
                ->color($profitColor)
                ->chart($this->getProfitSparkData()),

            Stat::make('Margen de Ganancia', $profitMargin . '%')
                ->description(($marginDiff >= 0 ? '+' : '') . "{$marginDiff}% vs trimestre anterior")
                ->descriptionColor($marginColor)
                ->color($marginColor)
                ->chart($this->getMarginSparkData()),
        ];
    }

    private function getRevenueSparkData(): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = round(Harvest::whereMonth('harvest_date', $month->month)
                ->whereYear('harvest_date', $month->year)
                ->sum('total_income'), 0);
        }
        return $data;
    }

    private function getCostSparkData(): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = round(HarvestCost::whereMonth('incurred_date', $month->month)
                ->whereYear('incurred_date', $month->year)
                ->sum('amount'), 0);
        }
        return $data;
    }

    private function getProfitSparkData(): array
    {
        $revenue = $this->getRevenueSparkData();
        $costs = $this->getCostSparkData();
        return array_map(fn ($r, $c) => $r - $c, $revenue, $costs);
    }

    private function getMarginSparkData(): array
    {
        $revenue = $this->getRevenueSparkData();
        $profit = $this->getProfitSparkData();
        return array_map(fn ($r, $p) => $r > 0 ? round(($p / $r) * 100, 1) : 0, $revenue, $profit);
    }
}
