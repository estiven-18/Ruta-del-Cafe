<?php

namespace App\Filament\Resources\Crops\Pages;

use App\Filament\Resources\Crops\CropResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCrop extends CreateRecord
{
    protected static string $resource = CropResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'activo';

        return $data;
    }
}
