<?php

namespace App\Exports;

use App\Models\HarvestCost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HarvestCostsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return HarvestCost::query()
            ->with(['harvest.crop.farm', 'harvest.crop.coffeeVariety', 'costCategory'])
            ->orderBy('incurred_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '# Costo',
            'Fecha',
            'Cosecha',
            'Finca',
            'Variedad',
            'Categoría',
            'Monto',
            'Descripción',
        ];
    }

    public function map($cost): array
    {
        $harvest = $cost->harvest;
        $crop = $harvest?->crop;
        $farm = $crop?->farm;
        $variety = $crop?->coffeeVariety;

        return [
            $cost->getKey(),
            $cost->incurred_date ? \Carbon\Carbon::parse($cost->incurred_date)->format('d/m/Y') : '',
            $harvest ? '#' . str_pad($harvest->getKey(), 3, '0', STR_PAD_LEFT) : '—',
            $farm?->name ?? 'Sin finca',
            $variety?->name ?? 'Sin variedad',
            $cost->costCategory?->name ?? 'Sin categoría',
            $cost->amount,
            $cost->description ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
