<?php

namespace App\Filament\Widgets;

use App\Models\Harvest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestHarvestsWidget extends TableWidget
{
    protected static ?int $sort = 6;

    protected static ?string $heading = 'Últimas Cosechas';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => Harvest::query()
                    ->with('crop.farm')
                    ->latest('harvest_date')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Código')
                    ->formatStateUsing(fn ($state) => '#' . str_pad($state, 3, '0', STR_PAD_LEFT))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('crop.farm.name')
                    ->label('Finca')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harvest_date')
                    ->label('Fecha')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_income')
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
