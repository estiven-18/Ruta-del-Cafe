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
                TextColumn::make('incurred_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->weight('bold')
                    ->description(function ($record) {
                        $crop = $record->harvest?->crop;
                        if (! $crop) return '—';
                        $farm = $crop->farm()->withTrashed()->first()?->name ?? '—';
                        return $farm;
                    }),
                TextColumn::make('harvest_id')
                    ->label('Cosecha')
                    ->numeric()
                    ->prefix('#')
                    ->sortable()
                    ->description(function ($record) {
                        $crop = $record->harvest?->crop;
                        if (! $crop) return null;
                        $variety = $crop->coffeeVariety?->name ?? '—';
                        return "{$variety}";
                    }),
                TextColumn::make('costCategory.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('Cop')
                    ->sortable()
                    ->color('success')
                    ->weight('bold')
                    ->summarize([
                        Sum::make()
                            ->label('Total')
                            ->money('Cop'),
                    ]),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(40)
                    ->searchable()
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
