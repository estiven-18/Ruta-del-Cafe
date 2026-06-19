<?php

namespace App\Filament\Resources\Producers\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProducersTable
{
    public static function configure(Table $table): Table
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
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Teléfono copiado')
                    ->copyMessageDuration(1500)
                    ->placeholder('—'),
                TextColumn::make('farms')
                    ->label('Fincas')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->farms()->withTrashed()->get()->pluck('name')->toArray();
                    }),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    DeleteAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    RestoreAction::make()
                        ->hidden(fn($record) => !$record->trashed()),
                ])
            ])
            ->defaultSort('name')
            ->emptyStateHeading('Sin productores registrados')
            ->emptyStateDescription('Agregue los productores asociados a las fincas.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
