<?php

namespace App\Filament\Resources\Crops\Schemas;

use App\Models\Farm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class CropForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('farm_id')
                    ->label('Finca')
                    ->relationship('farm', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(),
                Select::make('coffee_variety_id')
                    ->label('Variedad de café')
                    ->relationship('coffeeVariety', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('planting_date')
                    ->label('Fecha de siembra')
                    ->required()
                    ->beforeOrEqual('estimated_harvest_date')
                    ->validationMessages([
                        'before_or_equal' => 'La fecha de siembra debe ser anterior o igual a la cosecha estimada.',
                    ]),
                DatePicker::make('estimated_harvest_date')
                    ->label('Fecha estimada de cosecha')
                    ->default(null)
                    ->afterOrEqual('planting_date')
                    ->validationMessages([
                        'after_or_equal' => 'La cosecha estimada debe ser posterior o igual a la fecha de siembra.',
                    ]),
                TextInput::make('area_hectares')
                    ->label('Área (ha)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->rules([
                        fn (Get $get) => function ($attribute, $value, $fail) use ($get) {
                            $farm = Farm::find($get('farm_id'));
                            if ($farm && (float) $value > (float) $farm->total_area_hectares) {
                                $fail("El área del cultivo no puede superar el área total de la finca ({$farm->total_area_hectares} ha).");
                            }
                        },
                    ])
                    ->validationMessages([
                        'min' => 'El área no puede ser negativa.',
                    ]),
                TextInput::make('plant_count')
                    ->label('Número de plantas')
                    ->integer()
                    ->minValue(0)
                    ->default(null),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'cosechado' => 'Cosechado',
                        'abandonado' => 'Abandonado',
                    ])
                    ->default('activo')
                    ->required(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->maxLength(65535)
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
