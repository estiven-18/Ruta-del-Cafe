<?php

namespace App\Filament\Resources\CostCategories\RelationManagers;

use App\Filament\Resources\HarvestCosts\HarvestCostResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HarvestCostsRelationManager extends RelationManager
{
    protected static string $relationship = 'harvestCosts';

    protected static ?string $title = 'Costos con esta categoría';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('harvest.crop.farm.name')
                    ->label('Finca')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harvest.harvest_date')
                    ->label('Cosecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('incurred_date')
                    ->label('Fecha del costo')
                    ->date()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->placeholder('—')
                    ->limit(40),
            ])
            ->headerActions([
                Action::make('ver_costos')
                    ->label('Ir a Costos de Cosecha')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn () => HarvestCostResource::getUrl('index')),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => HarvestCostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
