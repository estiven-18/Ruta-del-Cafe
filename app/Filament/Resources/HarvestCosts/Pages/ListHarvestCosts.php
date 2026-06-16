<?php

namespace App\Filament\Resources\HarvestCosts\Pages;

use App\Filament\Resources\HarvestCosts\HarvestCostResource;
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
}
