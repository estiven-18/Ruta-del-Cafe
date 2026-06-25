<?php

namespace App\Filament\Resources\ProfitabilityReports\Tables;

use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                SelectFilter::make('harvest_id')
                    ->label('Cosecha')
                    ->relationship('harvest', 'id')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todas'),
                Filter::make('date_range')
                    ->label('Rango de Fechas')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('Desde'),
                        \Filament\Forms\Components\DatePicker::make('to')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $v) => $q->where('calculated_at', '>=', $v))
                            ->when($data['to'] ?? null, fn ($q, $v) => $q->where('calculated_at', '<=', $v));
                    }),
                SelectFilter::make('profitability_status')
                    ->label('Estado de Rentabilidad')
                    ->options([
                        'profitable' => 'Rentable',
                        'break_even' => 'Punto de Equilibrio',
                        'loss' => 'Pérdida',
                    ])
                    ->query(fn ($query, array $data) => match ($data['value'] ?? null) {
                        'profitable' => $query->where('profitability_percentage', '>', 0),
                        'break_even' => $query->where('profitability_percentage', '=', 0),
                        'loss' => $query->where('profitability_percentage', '<', 0),
                        default => $query,
                    })
                    ->placeholder('Todos'),
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
