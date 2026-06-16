<?php

namespace App\Filament\Resources\Crops\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CropsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('farm.name')
                    ->label('Finca')
                    ->searchable(),
                TextColumn::make('coffeeVariety.name')
                    ->label('Variedad')
                    ->searchable(),
                TextColumn::make('planting_date')
                    ->label('Siembra')
                    ->date()
                    ->sortable(),
                TextColumn::make('estimated_harvest_date')
                    ->label('Cosecha estimada')
                    ->date()
                    ->sortable(),
                TextColumn::make('area_hectares')
                    ->label('Área (ha)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('plant_count')
                    ->label('Plantas')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'cosechado' => 'warning',
                        'abandonado' => 'gray',
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
