<?php

namespace App\Filament\Resources\CostCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Ej: Mano de obra, Fertilizante, Transporte')
                    ->autofocus()
                    ->columnSpanFull()
                    ->validationMessages([
                        'max' => 'El nombre no puede tener más de :max caracteres.',
                    ]),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(200)
                    ->rows(3)
                    ->placeholder('Describa el tipo de costo...')
                    ->columnSpanFull()
                    ->validationMessages([
                        'max' => 'La descripción no puede tener más de :max caracteres.',
                    ]),
            ]);
    }
}
