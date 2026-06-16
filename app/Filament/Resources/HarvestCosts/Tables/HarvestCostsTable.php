<?php

namespace App\Filament\Resources\HarvestCosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HarvestCostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('harvest.harvest_date')
                    ->label('Fecha de cosecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('harvest.crop.farm.name')
                    ->label('Finca')
                    ->searchable(),
                TextColumn::make('harvest.crop.coffeeVariety.name')
                    ->label('Variedad')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('costCategory.name')
                    ->label('Categoría')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('Cop')
                    ->sortable(),
                TextColumn::make('incurred_date')
                    ->label('Fecha del gasto')
                    ->date()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
