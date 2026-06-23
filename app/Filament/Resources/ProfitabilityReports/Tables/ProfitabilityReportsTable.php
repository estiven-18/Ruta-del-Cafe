<?php

namespace App\Filament\Resources\ProfitabilityReports\Tables;

use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProfitabilityReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('harvest_id')
                    ->label('Cosecha')
                    ->numeric()
                    ->prefix('#')
                    ->sortable()
                    ->weight('bold')
                    ->description(function ($record) {
                        $crop = $record->harvest?->crop;
                        if (! $crop) return null;
                        $farm = $crop->farm()->withTrashed()->first()?->name ?? '—';
                        $variety = $crop->coffeeVariety?->name ?? '—';
                        return "{$farm} · {$variety}";
                    }),
                TextColumn::make('total_income')
                    ->label('Ingresos')
                    ->money('Cop')
                    ->sortable()
                    ->color('success')
                    ->weight('bold')
                    ->summarize([
                        Sum::make()->label('Total')->money('Cop'),
                    ]),
                TextColumn::make('total_costs')
                    ->label('Costos')
                    ->money('Cop')
                    ->sortable()
                    ->color('warning')
                    ->summarize([
                        Sum::make()->label('Total')->money('Cop'),
                    ]),
                TextColumn::make('net_profit')
                    ->label('Ganancia')
                    ->money('Cop')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->summarize([
                        Sum::make()->label('Total')->money('Cop'),
                    ]),
                TextColumn::make('profitability_percentage')
                    ->label('Rentabilidad')
                    ->numeric(2)
                    ->suffix('%')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($state) => match (true) {
                        $state >= 30 => 'success',
                        $state >= 15 => 'info',
                        $state >= 0 => 'warning',
                        default => 'danger',
                    })
                    ->summarize([
                        Average::make()->label('Promedio')->numeric(2)->suffix('%'),
                    ]),
                TextColumn::make('calculated_at')
                    ->label('Calculado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('profitability_percentage')
                    ->label('Rentabilidad')
                    ->options([
                        'high' => 'Alta (≥30%)',
                        'medium' => 'Media (15-30%)',
                        'low' => 'Baja (0-15%)',
                        'negative' => 'Negativa (<0%)',
                    ])
                    ->query(fn ($query, array $data) => match ($data['value'] ?? null) {
                        'high' => $query->where('profitability_percentage', '>=', 30),
                        'medium' => $query->whereBetween('profitability_percentage', [15, 29.99]),
                        'low' => $query->whereBetween('profitability_percentage', [0, 14.99]),
                        'negative' => $query->where('profitability_percentage', '<', 0),
                        default => $query,
                    })
                    ->placeholder('Todas'),
            ])
            ->recordActions([
            ])
            ->recordClasses(fn ($record) => $record->trashed() ? ['bg-danger-50', 'dark:bg-danger-950'] : [])
            ->defaultSort('harvest_id', 'desc')
            ->emptyStateHeading('Sin reportes generados')
            ->emptyStateDescription('Los reportes se crean automáticamente al visitar esta página.')
            ->emptyStateIcon('heroicon-o-chart-bar');
    }
}
