<?php

namespace Database\Seeders;

use App\Models\CostCategory;
use Illuminate\Database\Seeder;

class CostCategorySeeder extends Seeder
{
    public function run(): void
    {
        CostCategory::create([
            'name' => 'Fertilizantes',
            'description' => 'Gastos en abonos y fertilizantes para el cultivo.',
        ]);

        CostCategory::create([
            'name' => 'Mano de obra',
            'description' => 'Pagos a trabajadores y jornaleros.',
        ]);

        CostCategory::create([
            'name' => 'Maquinaria',
            'description' => 'Costos de alquiler o mantenimiento de maquinaria.',
        ]);
    }
}
