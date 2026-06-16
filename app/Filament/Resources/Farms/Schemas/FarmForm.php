<?php

namespace App\Filament\Resources\Farms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FarmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre de la finca')
                    ->maxLength(255)
                    ->required(),
                Textarea::make('location')
                    ->label('Ubicación')
                    ->maxLength(65535)
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('total_area_hectares')
                    ->label('Área total (ha)')
                    ->numeric()
                    ->step(0.01)
                    ->required(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->maxLength(65535)
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('producers')
                    ->label('Productores')
                    ->relationship('producers', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
