<?php

namespace App\Filament\Resources\CoffeeVarieties\Pages;

use App\Filament\Resources\CoffeeVarieties\CoffeeVarietyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCoffeeVariety extends EditRecord
{
    protected static string $resource = CoffeeVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
