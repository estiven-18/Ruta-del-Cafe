<?php

namespace App\Filament\Resources\Crops\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CropsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->withTrashed())
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('farm.name')
                    ->label('Finca')
                    ->limit(20)
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->getStateUsing(function ($record) {
                        return $record->farm()?->withTrashed()->first()?->name ?? '—';
                    }),
                TextColumn::make('coffeeVariety.name')
                    ->label('Variedad')
                    ->limit(20)
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('planting_date')
                    ->label('Siembra')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('estimated_harvest_date')
                    ->label('Cosecha estimada')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('area_hectares')
                    ->label('Área')
                    ->numeric(2)
                    ->suffix(' ha')
                    ->sortable()
                    ->summarize([
                        Sum::make()->label('Total'),
                    ]),
                TextColumn::make('plant_count')
                    ->label('Plantas')
                    ->numeric()
                    ->sortable()
                    ->summarize([
                        Sum::make()->label('Σ'),
                    ]),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'activo' => 'success',
                        'cosechado' => 'info',
                        'abandonado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'activo' => 'Activo',
                        'cosechado' => 'Cosechado',
                        'abandonado' => 'Abandonado',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(fn($record) => $record->trashed() ? ['bg-danger-50', 'dark:bg-danger-950'] : [])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'cosechado' => 'Cosechado',
                        'abandonado' => 'Abandonado',
                    ])
                    ->placeholder('Todos'),
                SelectFilter::make('farm_id')
                    ->label('Finca')
                    ->relationship('farm', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('coffee_variety_id')
                    ->label('Variedad')
                    ->relationship('coffeeVariety', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                actionGroup::make([
                    ViewAction::make()
                        ->label('Ver Detalles')
                        ->modalHeading('Detalles del Cultivo')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Cerrar')
                        ->hidden(fn($record) => $record->trashed())
                        ->infolist(fn($record): array => [
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('farm.name')
                                        ->label('Finca')
                                        ->placeholder('—'),
                                    TextEntry::make('coffeeVariety.name')
                                        ->label('Variedad')
                                        ->placeholder('—'),
                                    TextEntry::make('planting_date')
                                        ->label('Fecha de Siembra')
                                        ->date('d/m/Y')
                                        ->placeholder('—'),
                                    TextEntry::make('estimated_harvest_date')
                                        ->label('Cosecha Estimada')
                                        ->date('d/m/Y')
                                        ->placeholder('—'),
                                    TextEntry::make('area_hectares')
                                        ->label('Área')
                                        ->suffix(' ha')
                                        ->placeholder('—'),
                                    TextEntry::make('plant_count')
                                        ->label('Plantas')
                                        ->placeholder('—'),
                                    TextEntry::make('status')
                                        ->label('Estado')
                                        ->badge()
                                        ->color(fn(string $state): string => match ($state) {
                                            'activo' => 'success',
                                            'cosechado' => 'info',
                                            'abandonado' => 'danger',
                                            default => 'gray',
                                        })
                                        ->formatStateUsing(fn(string $state): string => match ($state) {
                                            'activo' => 'Activo',
                                            'cosechado' => 'Cosechado',
                                            'abandonado' => 'Abandonado',
                                            default => $state,
                                        }),
                                ]),
                            TextEntry::make('notes')
                                ->label('Notas')
                                ->placeholder('Sin notas')
                                ->columnSpanFull(),
                        ]),
                    \Filament\Actions\Action::make('change_status')
                        ->label('Cambiar Estado')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->modalHeading('Cambiar Estado del Cultivo')
                        ->modalSubmitActionLabel('Actualizar')
                        ->schema([
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Nuevo Estado')
                                ->options([
                                    'activo' => 'Activo',
                                    'cosechado' => 'Cosechado',
                                    'abandonado' => 'Abandonado',
                                ])
                                ->required(),
                        ])
                        ->action(fn($record, array $data) => $record->update(['status' => $data['status']]))
                        ->hidden(fn($record) => $record->trashed()),
                    EditAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    DeleteAction::make()
                        ->before(fn($record) => $record->update(['status' => 'abandonado']))
                        ->hidden(fn($record) => $record->trashed()),
                    RestoreAction::make()
                        ->hidden(fn($record) => !$record->trashed()),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Eliminar')
                        ->before(fn($records) => $records->each->update(['status' => 'abandonado'])),
                    RestoreBulkAction::make()->label('Restaurar'),
                    \Filament\Actions\BulkAction::make('mark_harvested')
                        ->label('Marcar Cosechado')
                        ->icon('heroicon-o-check-badge')
                        ->color('info')
                        ->action(fn($records) => $records->each->update(['status' => 'cosechado']))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('planting_date', 'desc')
            ->emptyStateHeading('Sin cultivos registrados')
            ->emptyStateDescription('Cree el primer cultivo para empezar a registrar cosechas.')
            ->emptyStateIcon('heroicon-o-square-3-stack-3d');
    }
}
