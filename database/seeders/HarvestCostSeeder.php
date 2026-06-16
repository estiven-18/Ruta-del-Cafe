<?php

namespace Database\Seeders;

use App\Models\HarvestCost;
use Illuminate\Database\Seeder;

class HarvestCostSeeder extends Seeder
{
    public function run(): void
    {
        HarvestCost::create([
            'harvest_id' => 1,
            'cost_category_id' => 1,
            'amount' => 1500.00,
            'incurred_date' => '2024-10-01',
            'description' => 'Fertilizante orgánico para la cosecha.',
        ]);

        HarvestCost::create([
            'harvest_id' => 1,
            'cost_category_id' => 2,
            'amount' => 3200.00,
            'incurred_date' => '2024-10-10',
            'description' => 'Pago a recolectores.',
        ]);
    }
}
