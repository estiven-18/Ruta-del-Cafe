<?php

namespace App\Filament\Resources\HarvestCosts\Pages;

use App\Filament\Resources\HarvestCosts\HarvestCostResource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditHarvestCost extends EditRecord
{
    protected static string $resource = HarvestCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('harvest_id')
                    ->label('Cosecha')
                    ->relationship('harvest', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Cosecha del {$record->harvest_date}")
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('cost_category_id')
                    ->label('Categoría de costo')
                    ->relationship('costCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->required(),
                DatePicker::make('incurred_date')
                    ->label('Fecha del gasto')
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(65535)
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
