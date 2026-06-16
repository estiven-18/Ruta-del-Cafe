<?php

namespace Database\Seeders;

use App\Models\Producer;
use Illuminate\Database\Seeder;

class ProducerSeeder extends Seeder
{
    public function run(): void
    {
        Producer::create([
            'name' => 'Juan Pérez',
            'document_number' => '12345678',
            'phone' => '555-0101',
            'notes' => 'Productor de café orgánico certificado.',
        ]);

        Producer::create([
            'name' => 'María López',
            'document_number' => '87654321',
            'phone' => '555-0102',
        ]);
    }
}
