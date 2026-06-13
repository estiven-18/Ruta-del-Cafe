<?php

namespace App\Filament\Resources\CoffeeVarieties\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CoffeeVarietyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('scientific_name')
                    ->label('Nombre científico')
                    ->maxLength(255)
                    ->default(null),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(255)
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('typical_maturity_months')
                    ->label('Meses de maduración típica')
                    ->maxLength(255)
                    ->numeric()
                    ->default(null),
                Toggle::make('is_resistant')
                    ->label('Resistente a enfermedades')
                    ->required(),
            ]);
    }
}
