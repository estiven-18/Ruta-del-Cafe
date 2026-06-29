<?php

namespace App\Exports;

use App\Models\QualityEvaluation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QualityEvaluationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return QualityEvaluation::query()
            ->with(['harvest.crop.farm', 'harvest.crop.coffeeVariety'])
            ->orderBy('evaluation_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '# Evaluación',
            'Fecha Evaluación',
            'Fecha Cosecha',
            'Finca',
            'Variedad',
            'Puntaje Final',
            'Calidad',
            'Evaluador',
            'Notas',
        ];
    }

    public function map($evaluation): array
    {
        $harvest = $evaluation->harvest;
        $crop = $harvest?->crop;
        $farm = $crop?->farm;
        $variety = $crop?->coffeeVariety;

        $gradeLabels = [
            'especial' => 'Especialidad',
            'alto' => 'Premium',
            'medio' => 'Comercial',
            'bajo' => 'Bajo Grado',
        ];

        return [
            $evaluation->getKey(),
            $evaluation->evaluation_date ? \Carbon\Carbon::parse($evaluation->evaluation_date)->format('d/m/Y') : '',
            $harvest?->harvest_date ? \Carbon\Carbon::parse($harvest->harvest_date)->format('d/m/Y') : '—',
            $farm?->name ?? 'Sin finca',
            $variety?->name ?? 'Sin variedad',
            $evaluation->final_score,
            $gradeLabels[$evaluation->quality_grade] ?? $evaluation->quality_grade,
            $this->getEvaluatorName($evaluation),
            $evaluation->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    private function getEvaluatorName(\App\Models\QualityEvaluation $evaluation): string
    {
        $evaluator = $evaluation->evaluator;

        if (is_string($evaluator)) {
            $decoded = json_decode($evaluator, true);
            if (is_array($decoded) && isset($decoded['name'])) {
                return $decoded['name'];
            }

            return $evaluator;
        }

        if (is_array($evaluator) && isset($evaluator['name'])) {
            return $evaluator['name'];
        }

        if (is_object($evaluator) && isset($evaluator->name)) {
            return $evaluator->name;
        }

        return '—';
    }
}
