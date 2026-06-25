<?php

namespace App\Filament\Resources\Producers\RelationManagers;

use App\Filament\Resources\Farms\FarmResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FarmsRelationManager extends RelationManager
{
    protected static string $relationship = 'farms';

    protected static ?string $title = 'Fincas asociadas';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('location')
                    ->label('Ubicación')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('total_area_hectares')
                    ->label('Área total (ha)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('ver_fincas')
                    ->label('Ir a Fincas')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('primary')
                    ->url(fn () => FarmResource::getUrl('index')),
            ]);
    }
}
