<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Section;


class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del usuario')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->maxLength(100)
                            ->required(),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->unique()
                            ->maxLength(100)
                            ->email()
                            ->required()
                            ->validationMessages([
                                'unique' => 'Este correo electrónico ya está registrado.',
                            ]),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->maxLength(255)
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn(?string $state): ?string => $state ? Hash::make($state) : null)
                            ->dehydrated(fn(?string $state): bool => filled($state)),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->required()
                            ->preload()
                            ->searchable()
                            ->validationMessages([
                                'required' => 'Debe seleccionar al menos un rol.',
                            ]),
                    ])
                    ->columns(2),
            ]);
    }
}
