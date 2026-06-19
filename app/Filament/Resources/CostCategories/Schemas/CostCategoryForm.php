<?php

namespace App\Filament\Resources\CostCategories\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CostCategoryForm
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
                            ->placeholder('Ej: Mano de obra, Fertilizante, Transporte')
                            ->autofocus()
                            ->validationMessages([
                                'max' => 'El nombre no puede tener más de :max caracteres.',
                            ])
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(255)
                            ->rows(3)
                            ->placeholder('Describa el tipo de costo...')
                            ->validationMessages([
                                'max' => 'La descripción no puede tener más de :max caracteres.',
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
