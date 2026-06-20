<?php

namespace App\Filament\Resources\HarvestCosts\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HarvestCostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('harvest_id')
                    ->label('Cosecha')
                    ->numeric()
                    ->prefix('#')
                    ->sortable(),
                TextColumn::make('harvest.crop.coffeeVariety.name')
                    ->label('Variedad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('incurred_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('costCategory.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('Cop')
                    ->sortable()
                    ->weight('bold')
                    ->summarize([
                        Sum::make()
                            ->label('Total')
                            ->money('Cop'),
                    ]),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(40)

                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),    
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('Papelera'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    DeleteAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    RestoreAction::make()
                        ->hidden(fn ($record) => !$record->trashed()),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('incurred_date', 'desc')
            ->emptyStateHeading('Sin costos registrados')
            ->emptyStateDescription('Los costos se asocian a cosechas específicas.')
            ->emptyStateIcon('heroicon-o-currency-dollar');
    }
}
