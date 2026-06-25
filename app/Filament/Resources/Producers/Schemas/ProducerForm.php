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
                            ->validationMessages([
                                'required' => 'Seleccione al menos una finca asociada.',
                            ])
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(80)
                            ->placeholder('Nombre completo del productor')
                            ->autofocus()
                            ->validationMessages([
                                'max' => 'El nombre no puede tener más de :max caracteres.',
                            ]),
                        TextInput::make('document_number')
                            ->label('Número de documento')
                            ->required()
                            ->minLength(5)
                            ->minValue(1)
                            ->integer()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('Cédula, RUC, DNI...')
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'min_digits' => 'El documento debe tener al menos :min dígitos.',
                                'max_digits' => 'El documento no puede tener más de :max dígitos.',
                                'max' => 'El documento no puede tener más de :max caracteres.',
                                'unique' => 'Este número de documento ya está registrado.',
                            ]),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->integer()
                            ->required()
                            ->minLength(7)
                            ->maxLength(20)
                            ->minValue(1)
                            ->placeholder('Ej: 88888888')
                            ->default(null)
                            ->validationMessages([
                                'min_digits' => 'El teléfono debe tener al menos :min dígitos.',
                                'max_digits' => 'El teléfono no puede tener más de :max dígitos.',
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
