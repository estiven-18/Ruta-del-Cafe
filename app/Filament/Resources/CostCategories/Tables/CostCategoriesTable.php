<?php

namespace App\Filament\Resources\CostCategories\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CostCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->placeholder('—')
                    ->limit(50),
                TextColumn::make('harvest_costs_count')
                    ->label('Usos')
                    ->counts('harvestCosts')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(fn ($record) => $record->trashed() ? ['bg-danger-50', 'dark:bg-danger-950'] : [])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Editar')
                        ->modalHeading('Editar Categoría de Costo')
                        ->modalSubmitActionLabel('Guardar')
                        ->hidden(fn ($record) => $record->trashed()),
                    DeleteAction::make()
                        ->label('Eliminar')
                        ->hidden(fn ($record) => $record->trashed()),
                    RestoreAction::make()
                        ->label('Restaurar')
                        ->visible(fn ($record) => $record->trashed()),
                ])
                
            ])
            ->defaultSort('name')
            ->emptyStateHeading('Sin categorías registradas')
            ->emptyStateDescription('Agregue las categorías de costos para clasificar los gastos de cosecha.')
            ->emptyStateIcon('heroicon-o-tag');
    }
}
