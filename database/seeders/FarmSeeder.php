<?php

namespace Database\Seeders;

use App\Models\Farm;
use App\Models\Producer;
use Illuminate\Database\Seeder;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        $juan = Producer::create(['name' => 'Juan Pérez', 'document_number' => '12345678', 'phone' => '555-0101']);
        $carlos = Producer::create(['name' => 'Carlos Gómez', 'document_number' => '23456789', 'phone' => '555-0102']);
        $maria = Producer::create(['name' => 'María López', 'document_number' => '87654321', 'phone' => '555-0103']);
        $pedro = Producer::create(['name' => 'Pedro Ramírez', 'document_number' => '34567890', 'phone' => '555-0104']);
        $ana = Producer::create(['name' => 'Ana Torres', 'document_number' => '45678901', 'phone' => '555-0105']);
        $luis = Producer::create(['name' => 'Luis Fernández', 'document_number' => '56789012', 'phone' => '555-0106']);

        $farm1 = Farm::create([
            'name' => 'Finca El Paraíso',
            'location' => 'Veracruz, México',
            'total_area_hectares' => 15.5,
            'notes' => 'Finca principal con certificación orgánica.',
        ]);
        $farm1->producers()->attach([$juan->id, $carlos->id]);

        $farm2 = Farm::create([
            'name' => 'Finca La Esperanza',
            'location' => 'Chiapas, México',
            'total_area_hectares' => 8.0,
        ]);
        $farm2->producers()->attach([$maria->id]);

        $farm3 = Farm::create([
            'name' => 'Finca El Progreso',
            'location' => 'Oaxaca, México',
            'total_area_hectares' => 22.0,
        ]);
        $farm3->producers()->attach([$pedro->id, $ana->id, $luis->id]);
    }
}
