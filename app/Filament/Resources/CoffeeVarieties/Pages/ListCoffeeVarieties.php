<?php

namespace App\Filament\Resources\CoffeeVarieties\Pages;

use App\Filament\Resources\CoffeeVarieties\CoffeeVarietyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoffeeVarieties extends ListRecords
{
    protected static string $resource = CoffeeVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
