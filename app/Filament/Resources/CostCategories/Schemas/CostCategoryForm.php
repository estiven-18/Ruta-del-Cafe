<?php

namespace App\Filament\Resources\CostCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255)
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(255)
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
