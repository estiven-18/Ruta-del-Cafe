<?php

namespace App\Filament\Resources\HarvestCosts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HarvestCostForm
{
    public static function configureForCreate(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cosecha')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('harvest_id')
                            ->label('Cosecha')
                            ->relationship('harvest', 'id')
                            ->getOptionLabelFromRecordUsing(fn($record) => "Cosecha #{$record->id} - {$record->harvest_date} ({$record->net_weight_kg} kg)")
                            ->required()
                            ->searchable()
                            ->preload()
                            ->validationMessages([
                                'required' => 'Seleccione una cosecha para asignar este costo.',
                            ])
                    ]),
                Section::make('Costos')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('costs')
                            ->label('Costos')
                            ->schema([
                                Select::make('cost_category_id')
                                    ->label('Categoría')
                                    ->relationship('costCategory', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->validationMessages([
                                        'required' => 'Seleccione una categoría para este costo.',
                                    ])
                                    ->required(),
                                TextInput::make('amount')
                                    ->label('Monto')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(1)
                                    ->required()
                                    ->prefix('$')
                                    ->validationMessages([
                                        'min' => 'El monto no puede ser negativo.',
                                        'required' => 'El monto es requerido.',
                                    ]),
                                DatePicker::make('incurred_date')
                                    ->label('Fecha del gasto')
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'La fecha del gasto es requerida.',
                                    ])
                                    ->native(false),
                                Textarea::make('description')
                                    ->label('Descripción')
                                    ->rows(2)
                                    ->placeholder('Detalle del gasto, factura, proveedor...')
                                    ->maxLength(65535)
                                    ->default(null),
                            ])
                            ->defaultItems(1)
                            ->deletable(fn(Repeater $component): bool => count($component->getState()) > 1)
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['cost_category_id'] ?? null),
                    ]),
            ]);
    }

    public static function configureForEdit(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Costo')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('harvest_id')
                            ->label('Cosecha')
                            ->relationship('harvest', 'id')
                            ->getOptionLabelFromRecordUsing(fn($record) => "Cosecha #{$record->id} - {$record->harvest_date} ({$record->net_weight_kg} kg)")
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(),
                        Select::make('cost_category_id')
                            ->label('Categoría')
                            ->relationship('costCategory', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Descripción')
                                    ->maxLength(65535),
                            ]),
                        TextInput::make('amount')
                            ->label('Monto')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->prefix('$'),
                        DatePicker::make('incurred_date')
                            ->label('Fecha del gasto')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),
                Section::make('Descripción')
                    ->schema([
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(4)
                            ->placeholder('Detalle del gasto, factura, proveedor, etc...')
                            ->maxLength(65535)
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
