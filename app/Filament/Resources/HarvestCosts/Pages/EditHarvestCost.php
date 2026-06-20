<?php

namespace App\Filament\Resources\HarvestCosts\Pages;

use App\Filament\Resources\HarvestCosts\HarvestCostResource;
use App\Filament\Resources\HarvestCosts\Schemas\HarvestCostForm;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditHarvestCost extends EditRecord
{
    protected static string $resource = HarvestCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return HarvestCostForm::configureForEdit($schema);
    }
}
