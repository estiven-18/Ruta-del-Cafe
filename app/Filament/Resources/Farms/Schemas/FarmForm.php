<?php

namespace App\Filament\Resources\Farms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FarmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de la finca')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Nombre descriptivo de la finca')
                            ->autofocus()
                            ->validationMessages([
                                'max' => 'El nombre no puede tener más de :max caracteres.',
                            ]),
                        TextInput::make('total_area_hectares')
                            ->label('Área total (ha)')
                            ->numeric()
                            ->MinValue(0.01)
                            ->maxValue(30)
                            ->step(0.01)
                            ->required()
                            ->placeholder('Ej: 15.50')
                            ->suffix('ha'),
                        Textarea::make('location')
                            ->label('Ubicación')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Vereda, municipio, departamento...')
                            ->rows(2)
                            ->columnSpanFull()
                            ->validationMessages([
                                'max' => 'La ubicación no puede tener más de :max caracteres.',
                            ]),
                        Select::make('producers')
                            ->label('Productores')
                            ->relationship('producers', 'name')
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->helperText('Seleccione los productores asociados a esta finca.'),
                        Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->maxLength(100)
                            ->placeholder('Información adicional sobre la finca...')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
