<?php

namespace App\Filament\Resources\Crops\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class HarvestsRelationManager extends RelationManager
{
    protected static string $relationship = 'harvests';

    protected static ?string $recordTitleAttribute = 'harvest_date';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('harvest_date')
                    ->label('Fecha de Cosecha')
                    ->required()
                    ->native(false),
                TextInput::make('gross_weight_kg')
                    ->label('Peso Bruto (kg)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required(),
                TextInput::make('defective_weight_kg')
                    ->label('Peso Defectuoso (kg)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0),
                TextInput::make('net_weight_kg')
                    ->label('Peso Neto (kg)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required(),
                TextInput::make('sale_price_per_kg')
                    ->label('Precio de Venta/kg')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->prefix('$'),
                TextInput::make('total_income')
                    ->label('Ingresos Totales')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->prefix('$'),
                Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->recordActions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),
                ])
                ->label('Acciones')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray'),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('harvest_date', 'desc')
            ->emptyStateHeading('Sin cosechas')
            ->emptyStateDescription('Registre la primera cosecha para este cultivo.')
            ->emptyStateIcon('heroicon-o-calendar');
    }
}
