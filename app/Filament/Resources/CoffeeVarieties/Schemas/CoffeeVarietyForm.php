<?php

namespace App\Filament\Resources\CoffeeVarieties\Schemas;

use Filament\Schemas\Components\Section;
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
                Section::make('Identificación')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Bourbon, Caturra, Geisha, Typica')
                            ->autofocus()
                            ->columnSpanFull(),
                        TextInput::make('scientific_name')
                            ->label('Nombre Científico')
                            ->placeholder('Coffea arabica, Coffea canephora...')
                            ->maxLength(255)
                            ->required()
                            ->columnSpanFull(),
                        Toggle::make('is_resistant')
                            ->label('Resistente a Plagas / Roya')
                            ->helperText('Marque si la variedad presenta resistencia natural.')
                            ->inline(false)
                            ->required()
                            ->onIcon('heroicon-o-shield-check')
                            ->offIcon('heroicon-o-shield-exclamation')
                            ->onColor('success')
                            ->offColor('gray'),
                    ])
                    ->columns(2),
                Section::make('Características')
                    ->schema([
                        TextInput::make('typical_maturity_months')
                            ->label('Maduración Típica')
                            ->numeric()
                            ->required()
                            ->suffix(' meses')
                            ->minValue(1)
                            ->maxValue(60)
                            ->helperText('Tiempo promedio desde siembra hasta primera cosecha.'),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(5)
                            ->placeholder('Notas, perfil de taza, recomendaciones de manejo, altitud óptima...')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns(2);
    }
}
