<?php

namespace App\Filament\Widgets;

use App\Models\Crop;
use App\Models\Farm;
use App\Models\Harvest;
use Filament\Widgets\Widget;

class AlertsWidget extends Widget
{
    protected string $view = 'filament.widgets.alerts';

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 2;

    public function getCropsReadyForHarvest(): int
    {
        return Crop::where('status', 'activo')
            ->where('estimated_harvest_date', '<=', now()->addDays(14))
            ->where('estimated_harvest_date', '>=', now())
            ->count();
    }

    public function getPendingEvaluations(): int
    {
        return Harvest::whereDoesntHave('qualityEvaluations')
            ->count();
    }

    public function getInactiveFarms(): int
    {
        return Farm::whereDoesntHave('crops')
            ->count();
    }

    public function getUpcomingFertilizations(): int
    {
        return Crop::where('status', 'activo')
            ->where('estimated_harvest_date', '>', now()->addDays(14))
            ->where('estimated_harvest_date', '<=', now()->addDays(30))
            ->count();
    }
}
