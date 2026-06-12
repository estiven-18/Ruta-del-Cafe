<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->maxLength(255)
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->maxLength(255)
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn(?string $state): ?string => $state ? Hash::make($state) : null)
                    ->dehydrated(fn(?string $state): bool => filled($state)),
            ]);
    }
}
