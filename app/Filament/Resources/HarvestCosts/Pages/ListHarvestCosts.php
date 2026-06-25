<?php

namespace App\Filament\Resources\HarvestCosts\Pages;

use App\Filament\Resources\HarvestCosts\HarvestCostResource;
use App\Filament\Resources\HarvestCosts\Widgets\HarvestCostStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHarvestCosts extends ListRecords
{
    protected static string $resource = HarvestCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            HarvestCostStatsWidget::class,
        ];
    }
}
