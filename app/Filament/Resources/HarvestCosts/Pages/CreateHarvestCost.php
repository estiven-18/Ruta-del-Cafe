<?php

namespace App\Filament\Resources\HarvestCosts\Pages;

use App\Filament\Resources\HarvestCosts\HarvestCostResource;
use App\Models\HarvestCost;
use Filament\Resources\Pages\CreateRecord;

class CreateHarvestCost extends CreateRecord
{
    protected static string $resource = HarvestCostResource::class;

    protected function handleRecordCreation(array $data): HarvestCost
    {
        $harvestId = $data['harvest_id'];

        foreach ($data['costs'] as $cost) {
            HarvestCost::create([
                'harvest_id' => $harvestId,
                'cost_category_id' => $cost['cost_category_id'],
                'amount' => $cost['amount'],
                'incurred_date' => $cost['incurred_date'],
                'description' => $cost['description'] ?? null,
            ]);
        }

        return HarvestCost::where('harvest_id', $harvestId)->first();
    }
}
