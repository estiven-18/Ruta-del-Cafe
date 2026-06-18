<?php

namespace App\Filament\Resources\ProfitabilityReports\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProfitabilityReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('harvest.crop.farm.name')
                    ->label('Finca')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harvest.crop.coffeeVariety.name')
                    ->label('Variedad'),
                TextColumn::make('harvest.harvest_date')
                    ->label('Cosecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_income')
                    ->label('Ingresos')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('total_costs')
                    ->label('Costos')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('net_profit')
                    ->label('Ganancia')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($state): string => $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('profitability_percentage')
                    ->label('Rentabilidad')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('%')
                    ->sortable()
                    ->color(fn ($state): string => $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('calculated_at')
                    ->label('Calculado')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
