<?php

namespace Database\Seeders;

use App\Models\QualityEvaluation;
use Illuminate\Database\Seeder;

class QualityEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        QualityEvaluation::create([
            'harvest_id' => 1,
            'evaluated_by' => 1,
            'evaluation_date' => '2024-10-20',
            'aroma_score' => 8.5,
            'flavor_score' => 8.0,
            'acidity_score' => 7.5,
            'body_score' => 8.0,
            'sweetness_score' => 9.0,
            'final_score' => 8.2,
            'quality_grade' => 'alto',
            'notes' => 'Café de buena calidad, perfil equilibrado.',
        ]);
    }
}
