<?php

namespace App\Filament\Resources\Harvests\Schemas;

use App\Models\Crop;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class HarvestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cultivo')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('crop_id')
                            ->label('Cultivo')
                            ->relationship('crop', 'id', fn ($query) => $query->with(['farm', 'coffeeVariety']))
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->farm?->name . ' - ' . $record->coffeeVariety?->name . ' (' . $record->planting_date . ')')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $crop = Crop::withTrashed()->find($state);
                                    if ($crop && $crop->planting_date) {
                                        $age = \Carbon\Carbon::parse($crop->planting_date)->diffInMonths(now());
                                        $set('crop_age_months', $age);
                                    }
                                }
                            })
                            ->validationMessages([
                                'required' => 'Seleccione un cultivo para la cosecha.',
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Producción')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('harvest_date')
                            ->label('Fecha de Cosecha')
                            ->required()
                            ->native(false)
                            ->default(now())
                            ->minDate(function (Get $get): ?string {
                                $cropId = $get('crop_id');
                                if (!$cropId) return null;
                                $crop = \App\Models\Crop::withTrashed()->find($cropId);
                                return $crop?->planting_date;
                            })
                            ->rules([
                                'after:2000-01-01',
                            ])
                            ->validationMessages([
                                'required' => 'La fecha de cosecha es requerida.',
                                'after' => 'La fecha no puede ser anterior al año 2000.',
                                'after_or_equal' => 'La fecha de cosecha no puede ser anterior a la fecha de siembra del cultivo.',
                            ]),
                        TextInput::make('gross_weight_kg')
                            ->label('Peso Bruto (kg)')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->suffix('kg')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                        TextInput::make('defective_weight_kg')
                            ->label('Peso Defectuoso (kg)')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->default(0)
                            ->suffix('kg')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                        TextInput::make('net_weight_kg')
                            ->label('Peso Neto (kg)')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->readOnly()
                            ->helperText('Se calcula automáticamente: Bruto - Defectuoso'),
                        TextInput::make('sale_price_per_kg')
                            ->label('Precio de Venta (por kg)')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->prefix('$')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                        TextInput::make('total_income')
                            ->label('Ingresos Totales')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->readOnly()
                            ->prefix('$')
                            ->helperText('Se calcula automáticamente: Peso Neto × Precio/kg'),
                    ])
                    ->columns(2),
                Section::make('Notas')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notas de la Cosecha')
                            ->rows(3)
                            ->placeholder('Condiciones climáticas, manejo, observaciones...')
                            ->maxLength(65535)
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function recalculate(Set $set, Get $get): void
    {
        $gross = (float) ($get('gross_weight_kg') ?? 0);
        $defective = (float) ($get('defective_weight_kg') ?? 0);
        $net = max(0, $gross - $defective);
        $set('net_weight_kg', round($net, 2));

        $price = (float) ($get('sale_price_per_kg') ?? 0);
        $income = $net * $price;
        $set('total_income', round($income, 2));
    }
}
