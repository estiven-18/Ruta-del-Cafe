<?php

namespace App\Exports;

use App\Models\ProfitabilityReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitabilityReportsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return ProfitabilityReport::query()
            ->with(['harvest.crop.farm', 'harvest.crop.coffeeVariety'])
            ->orderBy('harvest_id', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '# Reporte',
            'Cosecha',
            'Finca',
            'Variedad',
            'Ingresos',
            'Costos',
            'Ganancia Neta',
            'Margen (%)',
            'Calculado',
        ];
    }

    public function map($report): array
    {
        $harvest = $report->harvest;
        $crop = $harvest?->crop;
        $farm = $crop?->farm;
        $variety = $crop?->coffeeVariety;

        return [
            $report->getKey(),
            '#' . str_pad($report->harvest_id, 3, '0', STR_PAD_LEFT),
            $farm?->name ?? 'Sin finca',
            $variety?->name ?? 'Sin variedad',
            $report->total_income,
            $report->total_costs,
            $report->net_profit,
            $report->profitability_percentage,
            $report->calculated_at ? \Carbon\Carbon::parse($report->calculated_at)->format('d/m/Y H:i') : '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
