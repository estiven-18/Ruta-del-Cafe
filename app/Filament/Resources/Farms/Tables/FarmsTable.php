<?php

namespace App\Filament\Resources\Farms\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FarmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->limit(30)
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => Str::limit($record->location ?? 'Sin ubicación', 30)),
                TextColumn::make('producers')
                    ->label('Productores')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->producers()->withTrashed()->get()->pluck('name')->toArray();
                    }),
                TextColumn::make('total_area_hectares')
                    ->label('Área total (ha)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->label('Total')
                            ->suffix(' ha'),
                        Average::make()
                            ->label('Promedio')
                            ->suffix(' ha'),
                    ]),
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
            ->defaultSort('name')
            ->emptyStateHeading('Sin fincas registradas')
            ->emptyStateDescription('Comience creando la primera finca.')
            ->emptyStateIcon('heroicon-o-home-modern');
    }
}
