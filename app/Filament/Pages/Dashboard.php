<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FinancialStatsWidget;
use App\Filament\Widgets\LatestHarvestsWidget;
use App\Filament\Widgets\OperationalStatsWidget;
use App\Filament\Widgets\ProductionByFarmWidget;
use App\Filament\Widgets\ProductionChartWidget;
use App\Filament\Widgets\QualityDistributionWidget;
use App\Filament\Widgets\TopProducersWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int | array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            OperationalStatsWidget::class,
            FinancialStatsWidget::class,
            ProductionChartWidget::class,
            QualityDistributionWidget::class,
            TopProducersWidget::class,
            ProductionByFarmWidget::class,
            LatestHarvestsWidget::class,
        ];
    }
}
