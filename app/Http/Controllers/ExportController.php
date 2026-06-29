<?php

namespace App\Http\Controllers;

use App\Exports\HarvestsExport;
use App\Exports\HarvestCostsExport;
use App\Exports\QualityEvaluationsExport;
use App\Exports\ProfitabilityReportsExport;
use App\Models\ProfitabilityReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function harvests()
    {
        return Excel::download(new HarvestsExport, 'cosechas_' . now()->format('Y-m-d_His') . '.xlsx');
    }

    public function harvestCosts()
    {
        return Excel::download(new HarvestCostsExport, 'costos_cosecha_' . now()->format('Y-m-d_His') . '.xlsx');
    }

    public function qualityEvaluations()
    {
        return Excel::download(new QualityEvaluationsExport, 'evaluaciones_calidad_' . now()->format('Y-m-d_His') . '.xlsx');
    }

    public function profitabilityReportsExcel()
    {
        return Excel::download(new ProfitabilityReportsExport, 'reportes_rentabilidad_' . now()->format('Y-m-d_His') . '.xlsx');
    }

    public function profitabilityReportsPdf()
    {
        $reports = ProfitabilityReport::query()
            ->with(['harvest.crop.farm', 'harvest.crop.coffeeVariety'])
            ->orderBy('harvest_id', 'desc')
            ->get();

        $totalIncome = $reports->sum('total_income');
        $totalCosts = $reports->sum('total_costs');
        $netProfit = $reports->sum('net_profit');
        $avgMargin = $reports->avg('profitability_percentage') ?? 0;

        $pdf = Pdf::loadView('exports.profitability-pdf', compact('reports', 'totalIncome', 'totalCosts', 'netProfit', 'avgMargin'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reportes_rentabilidad_' . now()->format('Y-m-d_His') . '.pdf');
    }
}
