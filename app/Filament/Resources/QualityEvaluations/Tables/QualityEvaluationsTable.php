<?php

namespace App\Filament\Resources\QualityEvaluations\Tables;

use App\Models\CoffeeVariety;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QualityEvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('variety_name')
                    ->label('Variedad')
                    ->getStateUsing(fn ($record) => $record->harvest?->crop?->coffeeVariety?->name),
                TextColumn::make('harvest.harvest_date')
                    ->label('Cosecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('harvest.crop.farm.name')
                    ->label('Finca')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harvest.crop.planting_date')
                    ->label('Siembra')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('final_score')
                    ->label('Puntaje')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('quality_grade')
                    ->label('Calificación')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'especial' => 'success',
                        'alto' => 'primary',
                        'medio' => 'warning',
                        'bajo' => 'gray',
                    }),
                TextColumn::make('evaluator.name')
                    ->label('Evaluador')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('evaluation_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
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
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
