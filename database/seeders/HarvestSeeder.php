<?php

namespace Database\Seeders;

use App\Models\Harvest;
use Illuminate\Database\Seeder;

class HarvestSeeder extends Seeder
{
    public function run(): void
    {
        Harvest::create([
            'crop_id' => 3,
            'harvest_date' => '2024-10-15',
            'gross_weight_kg' => 2500,
            'defective_weight_kg' => 150,
            'net_weight_kg' => 2350,
            'sale_price_per_kg' => 3.50,
            'total_income' => 8225,
        ]);
    }
}
