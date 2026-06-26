<?php

namespace App\Filament\Widgets;

use App\Models\Harvest;
use App\Models\HarvestCost;
use App\Models\QualityEvaluation;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activity';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 2;

    public function getActivities(): array
    {
        $activities = [];

        $recentHarvests = Harvest::with('crop.farm')
            ->latest()
            ->limit(3)
            ->get();

        foreach ($recentHarvests as $harvest) {
            $activities[] = [
                'text' => "Cosecha HARV-{$harvest->id} registrada",
                'time' => $harvest->created_at->diffForHumans(),
                'detail' => $harvest->crop->farm->name ?? 'Sin finca',
                'icon' => 'heroicon-o-check-circle',
                'color' => 'success',
            ];
        }

        $recentCosts = HarvestCost::with('harvest')
            ->latest()
            ->limit(2)
            ->get();

        foreach ($recentCosts as $cost) {
            $activities[] = [
                'text' => "Costo de $" . number_format($cost->amount, 0, ',', '.') . " registrado",
                'time' => $cost->created_at->diffForHumans(),
                'detail' => $cost->description ?? 'Sin descripción',
                'icon' => 'heroicon-o-currency-dollar',
                'color' => 'warning',
            ];
        }

        $recentEvaluations = QualityEvaluation::with('harvest')
            ->latest()
            ->limit(2)
            ->get();

        foreach ($recentEvaluations as $evaluation) {
            $activities[] = [
                'text' => "Evaluación de calidad completada",
                'time' => $evaluation->created_at->diffForHumans(),
                'detail' => "Puntaje: {$evaluation->final_score} - {$evaluation->quality_grade}",
                'icon' => 'heroicon-o-star',
                'color' => 'primary',
            ];
        }

        usort($activities, fn ($a, $b) => strtotime($b['time']) - strtotime($a['time']));

        return array_slice($activities, 0, 5);
    }
}
