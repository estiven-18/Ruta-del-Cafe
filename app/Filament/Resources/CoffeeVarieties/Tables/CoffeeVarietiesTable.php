<?php

namespace App\Filament\Resources\CoffeeVarieties\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoffeeVarietiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->weight('bold'),
                TextColumn::make('scientific_name')
                    ->label('Nombre Científico')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('typical_maturity_months')
                    ->label('Maduración')
                    ->numeric()
                    ->suffix(' meses')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('—')
                    ->alignCenter(),
                IconColumn::make('is_resistant')
                    ->label('Resistente')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable()
                    ->alignCenter(),
                textColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->placeholder('—'),
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
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_resistant')
                    ->label('Resistente a Plagas')
                    ->placeholder('Todas')
                    ->trueLabel('Resistentes')
                    ->falseLabel('Susceptibles'),
                Filter::make('typical_maturity')
                    ->label('Maduración (meses)')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('from')
                            ->label('Desde')
                            ->numeric()
                            ->suffix(' meses')
                            ->minValue(1),
                        \Filament\Forms\Components\TextInput::make('to')
                            ->label('Hasta')
                            ->numeric()
                            ->suffix(' meses')
                            ->minValue(1),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $v) => $q->where('typical_maturity_months', '>=', $v))
                            ->when($data['to'] ?? null, fn ($q, $v) => $q->where('typical_maturity_months', '<=', $v));
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Ver Detalles')
                        ->modalHeading('Detalles de la Variedad')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Cerrar')
                        ->hidden(fn ($record) => $record->trashed())
                        ->infolist(fn ($record): array => [
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Nombre')
                                        ->placeholder('—'),
                                    TextEntry::make('scientific_name')
                                        ->label('Nombre Científico')
                                        ->placeholder('—'),
                                    TextEntry::make('typical_maturity_months')
                                        ->label('Maduración')
                                        ->suffix(' meses')
                                        ->placeholder('—'),
                                    IconEntry::make('is_resistant')
                                        ->label('Resistente')
                                        ->boolean()
                                        ->trueIcon('heroicon-o-shield-check')
                                        ->falseIcon('heroicon-o-shield-exclamation')
                                        ->trueColor('success')
                                        ->falseColor('danger'),
                                ]),
                            TextEntry::make('description')
                                ->label('Descripción')
                                ->placeholder('Sin descripción')
                                ->columnSpanFull(),
                        ]),
                    EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    DeleteAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    RestoreAction::make()
                        ->hidden(fn ($record) => !$record->trashed()),
                ])
            ])
            ->defaultSort('name')
            ->emptyStateHeading('Sin variedades registradas')
            ->emptyStateDescription('Agregue las variedades de café que cultiva para clasificar sus cosechas.')
            ->emptyStateIcon('heroicon-o-sparkles');
    }
}
