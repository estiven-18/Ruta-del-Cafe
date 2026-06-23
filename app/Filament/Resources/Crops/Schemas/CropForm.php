<?php

namespace App\Filament\Resources\Crops\Schemas;

use App\Models\CoffeeVariety;
use App\Models\Farm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CropForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asignación')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('farm_id')
                            ->label('Finca')
                            ->relationship('farm', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $farm = Farm::withTrashed()->find($state);
                                    if ($farm) {
                                        $set('area_hectares', min((float) $farm->total_area_hectares, 1.0));
                                    }
                                }
                            })
                            ->validationMessages([
                                'required' => 'Seleccione una finca para el cultivo.',
                            ]),
                        Select::make('coffee_variety_id')
                            ->label('Variedad de Café')
                            ->relationship('coffeeVariety', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $variety = CoffeeVariety::find($state);
                                    if ($variety && $variety->typical_maturity_months) {
                                        $plantingDate = $get('planting_date');
                                        if ($plantingDate) {
                                            $harvest = \Carbon\Carbon::parse($plantingDate)->addMonths($variety->typical_maturity_months);
                                            $set('estimated_harvest_date', $harvest->format('Y-m-d'));
                                        }
                                    }
                                }
                            })
                            ->validationMessages([
                                'required' => 'Seleccione una variedad de café.',
                            ]),
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'activo' => 'Activo',
                                'cosechado' => 'Cosechado',
                                'abandonado' => 'Abandonado',
                            ])
                            ->default('activo')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),
                Section::make('Detalles del Cultivo')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('planting_date')
                            ->label('Fecha de Siembra')
                            ->required()
                            ->native(false)
                            ->default(now())
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $varietyId = $get('coffee_variety_id');
                                    if ($varietyId) {
                                        $variety = CoffeeVariety::find($varietyId);
                                        if ($variety && $variety->typical_maturity_months) {
                                            $harvest = \Carbon\Carbon::parse($state)->addMonths($variety->typical_maturity_months);
                                            $set('estimated_harvest_date', $harvest->format('Y-m-d'));
                                        }
                                    }
                                }
                            })
                            ->rules([
                                'after:2000-01-01',
                            ])
                            ->validationMessages([
                                'required' => 'La fecha de siembra es requerida.',
                                'after' => 'La fecha de siembra no puede ser anterior al año 2000.',
                            ]),
                        DatePicker::make('estimated_harvest_date')
                            ->label('Cosecha Esperada')
                            ->native(false)
                            ->readOnly()
                            ->helperText('Se calcula automáticamente según la variedad.')
                            ->rules(['after:planting_date'])
                            ->validationMessages([
                                'after' => 'La cosecha esperada debe ser posterior a la fecha de siembra.',
                            ]),
                        TextInput::make('area_hectares')
                            ->label('Área del Cultivo (ha)')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0.01)
                            ->placeholder('Ej: 2.50')
                            ->suffix('ha')
                            ->live()
                            ->validationMessages([
                                'required' => 'El área del cultivo es requerida.',
                                'min' => 'El área debe ser mayor a 0.',
                            ]),
                        TextInput::make('plant_count')
                            ->label('Número de Plantas')
                            ->numeric()
                            ->maxLength(100000)
                            ->required()
                            ->minValue(0)
                            ->placeholder('Ej: 1000')
                            ->validationMessages([
                                'required' => 'El numero de plantas es requerido.',
                                'min' => 'El numero de plantas debe ser mayor a 0.',
                                'max_digits' => 'Inserte una cantidad valida'
                            ])
                            ->live(),
                        Placeholder::make('density')
                            ->label('Densidad (plantas/ha)')
                            ->content(function (callable $get) {
                                $area = (float) $get('area_hectares');
                                $plants = (int) $get('plant_count');
                                if ($area > 0 && $plants > 0) {
                                    return number_format($plants / $area, 0) . ' plantas/ha';
                                }
                                return '—';
                            }),
                        Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->placeholder('Información adicional sobre el cultivo...')
                            ->maxLength(65535)
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
