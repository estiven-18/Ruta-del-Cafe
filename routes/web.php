<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

Route::prefix('admin/exports')->middleware(['auth', 'web'])->group(function () {
    Route::get('harvests', [ExportController::class, 'harvests'])->name('exports.harvests');
    Route::get('harvest-costs', [ExportController::class, 'harvestCosts'])->name('exports.harvest-costs');
    Route::get('quality-evaluations', [ExportController::class, 'qualityEvaluations'])->name('exports.quality-evaluations');
    Route::get('profitability-reports/excel', [ExportController::class, 'profitabilityReportsExcel'])->name('exports.profitability-reports.excel');
    Route::get('profitability-reports/pdf', [ExportController::class, 'profitabilityReportsPdf'])->name('exports.profitability-reports.pdf');
});
