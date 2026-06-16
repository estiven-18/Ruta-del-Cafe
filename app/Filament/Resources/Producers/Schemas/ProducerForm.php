<?php

namespace App\Filament\Resources\Producers\Schemas;

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
                Select::make('farms')
                    ->label('Fincas')
                    ->relationship('farms', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('document_number')
                    ->label('Número de documento')
                    ->maxLength(255)
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->integer()
                    ->maxLength(255)
                    ->default(null),
                Textarea::make('notes')
                    ->label('Notas')
                    ->maxLength(65535)
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
