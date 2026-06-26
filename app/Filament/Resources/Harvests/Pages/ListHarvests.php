<?php

namespace App\Filament\Resources\Harvests\Pages;

use App\Filament\Resources\Harvests\HarvestResource;
use App\Filament\Resources\Harvests\Widgets\HarvestStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHarvests extends ListRecords
{
    protected static string $resource = HarvestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            HarvestStatsWidget::class,
        ];
    }
}
