<?php

namespace App\Filament\Resources\Harvests\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HarvestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withTrashed())
            ->columns([
                TextColumn::make('harvest_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->weight('bold')
                    ->description(function ($record) {
                        $crop = $record->crop;
                        if (! $crop) return '—';
                        $farm = $crop->farm()?->withTrashed()->first();
                        return $farm?->name ?? '—';
                    }),
                TextColumn::make('crop.coffeeVariety.name')
                    ->label('Variedad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gross_weight_kg')
                    ->label('Peso Bruto')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('defective_weight_kg')
                    ->label('Defectuoso')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('net_weight_kg')
                    ->label('Peso Neto')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable()
                    ->weight('bold')
                    ->summarize([
                        Sum::make()->label('Total')->numeric(2)->suffix(' kg'),
                    ]),
                TextColumn::make('sale_price_per_kg')
                    ->label('Precio/kg')
                    ->money('Cop')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_income')
                    ->label('Ingresos')
                    ->money('Cop')
                    ->sortable()
                    ->color('success')
                    ->weight('bold')
                    ->summarize([
                        Sum::make()->label('Total')->money('Cop'),
                    ]),
                TextColumn::make('qualityEvaluations.quality_grade')
                    ->label('Calidad')
                    ->getStateUsing(fn ($record) => $record->qualityEvaluations->first()?->quality_grade ?? 'Sin evaluar')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'especial' => 'success',
                        'alto' => 'info',
                        'medio' => 'warning',
                        'bajo' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(fn ($record) => $record->trashed() ? ['bg-danger-50', 'dark:bg-danger-950'] : [])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('crop_id')
                    ->label('Finca')
                    ->relationship('crop.farm', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('variety')
                    ->label('Variedad')
                    ->relationship('crop.coffeeVariety', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->label('Ver Detalles'),
                    EditAction::make()->label('Editar'),
                    \Filament\Actions\ReplicateAction::make()->label('Duplicar'),
                    \Filament\Actions\Action::make('add_cost')
                        ->label('Agregar Costo')
                        ->icon('heroicon-o-plus-circle')
                        ->color('warning')
                        ->url(fn ($record) => \App\Filament\Resources\HarvestCosts\HarvestCostResource::getUrl('create', ['harvest_id' => $record->id])),
                    DeleteAction::make()->label('Eliminar')
                        ->hidden(fn ($record) => $record->trashed()),
                    RestoreAction::make()->label('Restaurar')
                        ->visible(fn ($record) => $record->trashed()),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Eliminar'),
                    RestoreBulkAction::make()->label('Restaurar'),
                ]),
            ])
            ->defaultSort('harvest_date', 'desc')
            ->emptyStateHeading('Sin cosechas registradas')
            ->emptyStateDescription('Las cosechas se generan a partir de los cultivos activos.')
            ->emptyStateIcon('heroicon-o-cube-transparent');
    }
}
