<?php

namespace App\Filament\Resources\ProfitabilityReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProfitabilityReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('harvest_id')
                    ->label('Cosecha')
                    ->relationship('harvest', 'id', modifyQueryUsing: fn ($query) => $query
                        ->whereDoesntHave('profitabilityReport', fn ($q) => $q->whereNull('deleted_at'))
                        ->when(request()->route('record'), fn ($q) => $q->orWhere('id', \App\Models\ProfitabilityReport::find(request()->route('record'))?->harvest_id))
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Cosecha del {$record->harvest_date} - {$record->crop->farm->name}")
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('total_income')
                    ->label('Ingresos totales')
                    ->numeric()
                    ->step(0.01)
                    ->readOnly()
                    ->required(),
                TextInput::make('total_costs')
                    ->label('Costos totales')
                    ->numeric()
                    ->step(0.01)
                    ->readOnly()
                    ->required(),
                TextInput::make('net_profit')
                    ->label('Ganancia neta')
                    ->numeric()
                    ->step(0.01)
                    ->readOnly()
                    ->required(),
                TextInput::make('profitability_percentage')
                    ->label('Rentabilidad (%)')
                    ->numeric()
                    ->step(0.01)
                    ->suffix('%')
                    ->readOnly()
                    ->required(),
                DatePicker::make('calculated_at')
                    ->label('Calculado el')
                    ->required()
                    ->readOnly(),
            ]);
    }

    public static function recalculate(Set $set, Get $get): void
    {
        $harvestId = $get('harvest_id');
        if (!$harvestId) {
            $set('total_income', 0);
            $set('total_costs', 0);
            $set('net_profit', 0);
            $set('profitability_percentage', 0);
            $set('calculated_at', now()->format('Y-m-d H:i:s'));
            return;
        }

        $harvest = \App\Models\Harvest::with('harvestCosts')->find($harvestId);

        $income = (float) ($harvest?->total_income ?? 0);
        $costs = (float) ($harvest?->harvestCosts->sum('amount') ?? 0);
        $net = round($income - $costs, 2);
        $percentage = $costs > 0 ? round(($net / $costs) * 100, 2) : 0;

        $set('total_income', round($income, 2));
        $set('total_costs', round($costs, 2));
        $set('net_profit', $net);
        $set('profitability_percentage', $percentage);
        $set('calculated_at', now()->format('Y-m-d H:i:s'));
    }
}
