<?php

namespace App\Filament\Resources\Producers\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProducerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Productor')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('farms')
                            ->label('Fincas asociadas')
                            ->relationship('farms', 'name')
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Seleccione las fincas donde trabaja este productor.')
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nombre completo del productor')
                            ->autofocus()
                            ->validationMessages([
                                'max' => 'El nombre no puede tener más de :max caracteres.',
                            ]),
                        TextInput::make('document_number')
                            ->label('Número de documento')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('Cédula, RUC, DNI...')
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'max' => 'El documento no puede tener más de :max caracteres.',
                                'unique' => 'Este número de documento ya está registrado.',
                            ]),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->integer()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('Ej: 88888888')
                            ->default(null)
                            ->validationMessages([
                                'max' => 'El teléfono no puede tener más de :max caracteres.',
                            ]),
                        Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->placeholder('Información adicional sobre el productor...')
                            ->maxLength(255)
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
