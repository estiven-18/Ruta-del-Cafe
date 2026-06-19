<?php

namespace App\Filament\Resources\CoffeeVarieties\RelationManagers;

use App\Filament\Resources\Crops\CropResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CropsRelationManager extends RelationManager
{
    protected static string $relationship = 'crops';

    protected static ?string $title = 'Cultivos con esta variedad';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('farm.name')
                    ->label('Finca')
                    ->searchable()
                    ->sortable(),
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
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'cosechado' => 'info',
                        'abandonado' => 'danger',
                    }),
                TextColumn::make('harvests_count')
                    ->label('Cosechas')
                    ->counts('harvests')
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('ver_cultivos')
                    ->label('Ir a Cultivos')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn () => CropResource::getUrl('index')),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => CropResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
