<?php

namespace App\Filament\Resources\Harvests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HarvestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('crop.id')
                    ->label('Cultivo')
                    ->getStateUsing(fn ($record) => "{$record->crop->farm->name} - {$record->crop->coffeeVariety->name} ({$record->crop->planting_date})")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harvest_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('gross_weight_kg')
                    ->label('Peso bruto (kg)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('defective_weight_kg')
                    ->label('Defectuoso (kg)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('net_weight_kg')
                    ->label('Peso neto (kg)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('sale_price_per_kg')
                    ->label('Precio/kg')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_income')
                    ->label('Ingresos')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('quality_grade')
                    ->label('Calificación')
                    ->getStateUsing(fn ($record) => $record->qualityEvaluations->first()?->quality_grade ?? 'Sin calificar')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'especial' => 'success',
                        'alto' => 'primary',
                        'medio' => 'warning',
                        'bajo' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                RestoreAction::make()
                    ->hidden(fn ($record) => !$record->trashed()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
