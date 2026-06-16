<?php

namespace App\Filament\Resources\HarvestCosts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HarvestCostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('harvest_id')
                    ->label('Cosecha')
                    ->relationship('harvest', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->crop->farm->name} - {$record->crop->coffeeVariety->name} ({$record->crop->planting_date}) - Cosecha el {$record->harvest_date}")
                    ->searchable()
                    ->preload()
                    ->required(),
                Repeater::make('costs')
                    ->label('Costos')
                    ->schema([
                        Select::make('cost_category_id')
                            ->label('Categoría')
                            ->relationship('costCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(1)
                            ->required()
                            ->validationMessages([
                                'min' => 'El monto no puede ser negativo.',
                            ]),
                        DatePicker::make('incurred_date')
                            ->label('Fecha del gasto')
                            ->required(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(65535)
                            ->default(null),
                    ])
                    ->defaultItems(1)
                    ->collapsible(),
            ]);
    }
}
