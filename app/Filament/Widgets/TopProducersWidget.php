<?php

namespace App\Filament\Widgets;

use App\Models\Producer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TopProducersWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Producer::query()
                    ->select('producers.id', 'producers.name')
                    ->join('farm_producer', 'farm_producer.producer_id', '=', 'producers.id')
                    ->join('farms', 'farms.id', '=', 'farm_producer.farm_id')
                    ->join('crops', 'crops.farm_id', '=', 'farms.id')
                    ->join('harvests', 'harvests.crop_id', '=', 'crops.id')
                    ->selectRaw('producers.id, producers.name, COUNT(DISTINCT farms.id) as farms_count, COALESCE(SUM(harvests.total_income), 0) as total_profit')
                    ->groupBy('producers.id', 'producers.name')
                    ->orderByDesc('total_profit')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Productor')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('farms_count')
                    ->label('Fincas')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('total_profit')
                    ->label('Ganancia')
                    ->money('USD')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
            ])
            ->paginated(false)
            ->striped();
    }
}
