<?php

namespace App\Filament\Resources\Harvests\Schemas;

use App\Models\Crop;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class HarvestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('crop_id')
                    ->label('Cultivo')
                    ->relationship('crop', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->farm->name} - {$record->coffeeVariety->name} se plantó el {$record->planting_date}")
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(),
                DatePicker::make('harvest_date')
                    ->label('Fecha de cosecha')
                    ->required()
                    ->rules([
                        fn (Get $get) => function ($attribute, $value, $fail) use ($get) {
                            $crop = Crop::find($get('crop_id'));
                            if ($crop && $value < $crop->planting_date) {
                                $fail("La fecha de cosecha no puede ser anterior a la siembra ({$crop->planting_date}).");
                            }
                        },
                    ]),
                TextInput::make('gross_weight_kg')
                    ->label('Peso bruto (kg)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('defective_weight_kg')
                    ->label('Peso defectuoso (kg)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('net_weight_kg')
                    ->label('Peso neto (kg)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->readOnly(),
                TextInput::make('sale_price_per_kg')
                    ->label('Precio de venta (por kg)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('total_income')
                    ->label('Ingresos totales')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->readOnly(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->maxLength(65535)
                    ->default(null)
                    ->columnSpanFull(),
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
