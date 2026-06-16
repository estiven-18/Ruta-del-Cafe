<?php

namespace Database\Seeders;

use App\Models\CoffeeVariety;
use Illuminate\Database\Seeder;

class CoffeeVarietySeeder extends Seeder
{
    public function run(): void
    {
        CoffeeVariety::create([
            'name' => 'Arábica',
            'scientific_name' => 'Coffea arabica',
            'description' => 'Variedad de café de alta calidad con sabor suave y aromático.',
            'typical_maturity_months' => 9,
            'is_resistant' => false,
        ]);

        CoffeeVariety::create([
            'name' => 'Robusta',
            'scientific_name' => 'Coffea canephora',
            'description' => 'Variedad más resistente y productiva, sabor más fuerte.',
            'typical_maturity_months' => 11,
            'is_resistant' => true,
        ]);
    }
}
