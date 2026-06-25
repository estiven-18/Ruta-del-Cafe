<?php

namespace App\Filament\Resources\Crops\RelationManagers;


use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Harvests\HarvestResource;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class HarvestsRelationManager extends RelationManager
{
    protected static string $relationship = 'harvests';

    protected static ?string $title = 'Cosechas';

    protected static ?string $recordTitleAttribute = 'harvest_date';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('harvest_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('gross_weight_kg')
                    ->label('Peso Bruto')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable(),
                TextColumn::make('defective_weight_kg')
                    ->label('Defectuoso')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable(),
                TextColumn::make('net_weight_kg')
                    ->label('Peso Neto')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('sale_price_per_kg')
                    ->label('Precio/kg')
                    ->money('Cop')
                    ->sortable(),
                TextColumn::make('total_income')
                    ->label('Ingresos')
                    ->money('Cop')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
                TextColumn::make('qualityEvaluations.quality_grade')
                    ->label('Calidad')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'especial' => 'success',
                        'alto' => 'info',
                        'medio' => 'warning',
                        'bajo' => 'danger',
                        default => 'gray',
                    })
                    ->default('Sin evaluar'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('ver_cosechas')
                    ->label('Ir a Cosechas')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('primary')
                    ->url(fn () => HarvestResource::getUrl('index')),
            ]);
    }
}
