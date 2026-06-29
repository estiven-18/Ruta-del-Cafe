<?php

namespace App\Exports;

use App\Models\Harvest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HarvestsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Harvest::query()
            ->with(['crop.farm', 'crop.coffeeVariety', 'qualityEvaluations'])
            ->orderBy('harvest_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '# Cosecha',
            'Fecha',
            'Finca',
            'Variedad',
            'Peso Bruto (kg)',
            'Peso Defectuoso (kg)',
            'Peso Neto (kg)',
            'Precio/kg',
            'Ingresos',
            'Calidad',
            'Notas',
        ];
    }

    public function map($harvest): array
    {
        $crop = $harvest->crop;
        $farm = $crop?->farm;
        $variety = $crop?->coffeeVariety;
        $evaluation = $harvest->qualityEvaluations->first();

        $gradeLabels = [
            'especial' => 'Especialidad',
            'alto' => 'Premium',
            'medio' => 'Comercial',
            'bajo' => 'Bajo Grado',
        ];

        return [
            $harvest->getKey(),
            $harvest->harvest_date ? \Carbon\Carbon::parse($harvest->harvest_date)->format('d/m/Y') : '',
            $farm?->name ?? 'Sin finca',
            $variety?->name ?? 'Sin variedad',
            $harvest->gross_weight_kg,
            $harvest->defective_weight_kg,
            $harvest->net_weight_kg,
            $harvest->sale_price_per_kg,
            $harvest->total_income,
            $evaluation ? ($gradeLabels[$evaluation->quality_grade] ?? $evaluation->quality_grade) : 'Sin evaluar',
            $harvest->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
