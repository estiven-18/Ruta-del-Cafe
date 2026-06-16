<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    public function run(): void
    {
        Crop::create([
            'farm_id' => 1,
            'coffee_variety_id' => 1,
            'planting_date' => '2025-03-15',
            'estimated_harvest_date' => '2025-12-15',
            'area_hectares' => 10.0,
            'plant_count' => 5000,
            'status' => 'activo',
        ]);

        Crop::create([
            'farm_id' => 1,
            'coffee_variety_id' => 2,
            'planting_date' => '2025-06-01',
            'estimated_harvest_date' => '2026-05-01',
            'area_hectares' => 5.5,
            'plant_count' => 2500,
            'status' => 'activo',
        ]);

        Crop::create([
            'farm_id' => 3,
            'coffee_variety_id' => 1,
            'planting_date' => '2024-01-20',
            'estimated_harvest_date' => '2024-10-20',
            'area_hectares' => 15.0,
            'plant_count' => 8000,
            'status' => 'cosechado',
        ]);
    }
}
