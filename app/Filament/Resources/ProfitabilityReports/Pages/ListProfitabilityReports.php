<?php

namespace App\Filament\Resources\ProfitabilityReports\Pages;

use App\Filament\Resources\ProfitabilityReports\ProfitabilityReportResource;
use App\Filament\Resources\ProfitabilityReports\Widgets\ProfitabilityReportStatsWidget;
use App\Models\Harvest;
use App\Models\ProfitabilityReport;
use Filament\Resources\Pages\ListRecords;

class ListProfitabilityReports extends ListRecords
{
    protected static string $resource = ProfitabilityReportResource::class;

    public function mount(): void
    {
        parent::mount();

        $harvests = Harvest::whereNull('deleted_at')
            ->with('harvestCosts')
            ->get();

        foreach ($harvests as $harvest) {
            $income = (float) ($harvest->total_income ?? 0);
            $costs = (float) ($harvest->harvestCosts->sum('amount') ?? 0);
            $net = round($income - $costs, 2);
            $percentage = $costs > 0 ? round(($net / $costs) * 100, 2) : 0;

            $data = [
                'total_income' => round($income, 2),
                'total_costs' => round($costs, 2),
                'net_profit' => $net,
                'profitability_percentage' => $percentage,
                'calculated_at' => now(),
            ];

            $report = ProfitabilityReport::withTrashed()
                ->where('harvest_id', $harvest->id)
                ->first();

            if ($report) {
                $report->restore();
                $report->update($data);
            } else {
                $report = ProfitabilityReport::create(
                    array_merge(['harvest_id' => $harvest->id], $data)
                );
            }
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProfitabilityReportStatsWidget::class,
        ];
    }
}
