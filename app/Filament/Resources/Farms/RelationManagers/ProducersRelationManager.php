<?php

namespace App\Filament\Resources\Farms\RelationManagers;

use App\Filament\Resources\Producers\ProducerResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class ProducersRelationManager extends RelationManager
{
    protected static string $relationship = 'producers';

    protected static ?string $title = 'Productores Asociados';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('document_number')
                    ->label('Documento')
                    ->placeholder('—'),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->placeholder('—'),
            ])
            ->filters([])
            ->headerActions([
                Action::make('ver_productores')
                    ->label('Ir a Productores')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('primary')
                    ->url(fn() => ProducerResource::getUrl('index')),
            ]);
    }
}
