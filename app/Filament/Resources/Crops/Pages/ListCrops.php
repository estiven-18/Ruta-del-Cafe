<?php

namespace App\Filament\Resources\Crops\Pages;

use App\Filament\Resources\Crops\CropResource;
use App\Filament\Resources\Crops\Widgets\CropStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCrops extends ListRecords
{
    protected static string $resource = CropResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CropStatsWidget::class,
        ];
    }
}
