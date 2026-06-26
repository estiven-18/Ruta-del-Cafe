<?php

namespace App\Filament\Widgets;

use App\Models\Crop;
use App\Models\Farm;
use App\Models\Harvest;
use App\Models\Producer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationalStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalProducers = Producer::count();
        $totalFarms = Farm::count();
        $activeCrops = Crop::where('status', 'activo')->count();
        $totalHarvests = Harvest::count();

        $producerGrowth = $this->getGrowth(Producer::class);
        $farmGrowth = $this->getGrowth(Farm::class);
        $cropGrowth = $this->getGrowth(Crop::class, ['status', 'activo']);
        $harvestGrowth = $this->getGrowth(Harvest::class);

        $producerColor = $producerGrowth >= 0 ? 'success' : 'danger';
        $producerIcon = $producerGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        $farmColor = $farmGrowth >= 0 ? 'success' : 'danger';
        $farmIcon = $farmGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        $cropColor = $cropGrowth >= 0 ? 'success' : 'danger';
        $cropIcon = $cropGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        $harvestColor = $harvestGrowth >= 0 ? 'success' : 'danger';
        $harvestIcon = $harvestGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        return [
            Stat::make('Total Productores', $totalProducers)
                ->description("{$producerGrowth}% este mes")
                ->descriptionIcon($producerIcon)
                ->descriptionColor($producerColor)
                ->color($producerColor)
                ->chart($this->getSparkData('producers')),

            Stat::make('Total Fincas', $totalFarms)
                ->description("{$farmGrowth}% este mes")
                ->descriptionIcon($farmIcon)
                ->descriptionColor($farmColor)
                ->color($farmColor)
                ->chart($this->getSparkData('farms')),

            Stat::make('Cultivos Activos', $activeCrops)
                ->description("{$cropGrowth}% este mes")
                ->descriptionIcon($cropIcon)
                ->descriptionColor($cropColor)
                ->color($cropColor)
                ->chart($this->getSparkData('crops')),

            Stat::make('Total Cosechas', $totalHarvests)
                ->description("{$harvestGrowth}% este mes")
                ->descriptionIcon($harvestIcon)
                ->descriptionColor($harvestColor)
                ->color($harvestColor)
                ->chart($this->getSparkData('harvests')),
        ];
    }

    private function getGrowth(string $model, array $extraWhere = []): int
    {
        $query = $model::query();
        foreach ($extraWhere as $i => $value) {
            if ($i % 2 === 0) {
                $query->where($value, $extraWhere[$i + 1] ?? null);
            }
        }

        $current = (clone $query)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        $previous = (clone $query)
            ->where('created_at', '>=', now()->subMonth()->startOfMonth())
            ->where('created_at', '<', now()->startOfMonth())
            ->count();

        return $previous > 0 ? round((($current - $previous) / max($previous, 1)) * 100) : ($current > 0 ? 100 : 0);
    }

    private function getSparkData(string $type): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $query = match ($type) {
                'producers' => Producer::where('created_at', '>=', $month->startOfMonth())
                    ->where('created_at', '<', $month->copy()->addMonth()),
                'farms' => Farm::where('created_at', '>=', $month->startOfMonth())
                    ->where('created_at', '<', $month->copy()->addMonth()),
                'crops' => Crop::where('status', 'activo')
                    ->where('created_at', '>=', $month->startOfMonth())
                    ->where('created_at', '<', $month->copy()->addMonth()),
                'harvests' => Harvest::where('created_at', '>=', $month->startOfMonth())
                    ->where('created_at', '<', $month->copy()->addMonth()),
            };
            $data[] = $query->count();
        }
        return $data;
    }
}
