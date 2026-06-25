<?php

namespace App\Filament\Resources\QualityEvaluations\Tables;

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
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QualityEvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('evaluation_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->weight('bold')
                    ->description(function ($record) {
                        $crop = $record->harvest?->crop;
                        if (! $crop) return '—';
                        $farm = $crop->farm()->withTrashed()->first()?->name ?? '—';
                        return $farm;
                    }),
                TextColumn::make('harvest_id')
                    ->label('Cosecha')
                    ->numeric()
                    ->prefix('#')
                    ->sortable()
                    ->description(function ($record) {
                        $crop = $record->harvest?->crop;
                        if (! $crop) return null;
                        $variety = $crop->coffeeVariety?->name ?? '—';
                        $farm = $crop->farm()->withTrashed()->first()?->name ?? '—';
                        return "{$farm} · {$variety}";
                    }),
                TextColumn::make('final_score')
                    ->label('Puntaje')
                    ->numeric(2)
                    ->sortable()
                    ->weight('bold')
                    ->color(fn($state) => match (true) {
                        $state >= 9 => 'success',
                        $state >= 8 => 'info',
                        $state >= 7 => 'warning',
                        default => 'danger',
                    })
                    ->summarize([
                        Average::make()
                            ->label('Promedio')
                            ->numeric(2),
                    ]),
                TextColumn::make('quality_grade')
                    ->label('Calidad')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'especial' => 'purple',
                        'alto' => 'success',
                        'medio' => 'warning',
                        'bajo' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'especial' => 'Especialidad',
                        'alto' => 'Premium',
                        'medio' => 'Comercial',
                        'bajo' => 'Bajo Grado',
                        default => 'N/D',
                    }),
                TextColumn::make('evaluator.name')
                    ->label('Evaluador')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TrashedFilter::make()
                    ->label('Papelera'),
                SelectFilter::make('quality_grade')
                    ->label('Calidad')
                    ->options([
                        'especial' => 'Specialty',
                        'alto' => 'Premium',
                        'medio' => 'Commercial',
                        'bajo' => 'Below Grade',
                    ])
                    ->placeholder('Todas'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Ver Detalles')
                        ->modalHeading('Detalles de la Evaluación')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Cerrar')
                        ->hidden(fn($record) => $record->trashed())
                        ->infolist(fn ($record): array => [
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('evaluation_date')
                                        ->label('Fecha de Evaluación')
                                        ->date('d/m/Y')
                                        ->placeholder('—'),
                                    TextEntry::make('harvest.harvest_date')
                                        ->label('Fecha de Cosecha')
                                        ->date('d/m/Y')
                                        ->placeholder('—'),
                                    TextEntry::make('harvest.crop.farm.name')
                                        ->label('Finca')
                                        ->placeholder('—'),
                                    TextEntry::make('harvest.crop.coffeeVariety.name')
                                        ->label('Variedad')
                                        ->placeholder('—'),
                                    TextEntry::make('final_score')
                                        ->label('Puntaje Final')
                                        ->numeric(2)
                                        ->placeholder('—'),
                                    TextEntry::make('quality_grade')
                                        ->label('Calidad')
                                        ->badge()
                                        ->color(fn(string $state): string => match ($state) {
                                            'especial' => 'purple',
                                            'alto' => 'success',
                                            'medio' => 'warning',
                                            'bajo' => 'danger',
                                            default => 'gray',
                                        })
                                        ->formatStateUsing(fn(string $state): string => match ($state) {
                                            'especial' => 'Especialidad',
                                            'alto' => 'Premium',
                                            'medio' => 'Comercial',
                                            'bajo' => 'Bajo Grado',
                                            default => 'N/D',
                                        }),
                                    TextEntry::make('evaluator.name')
                                        ->label('Evaluador')
                                        ->placeholder('—'),
                                    TextEntry::make('notes')
                                        ->label('Notas')
                                        ->placeholder('Sin notas')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    EditAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    DeleteAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    RestoreAction::make()
                        ->hidden(fn($record) => !$record->trashed()),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('evaluation_date', 'desc')
            ->emptyStateHeading('Sin evaluaciones registradas')
            ->emptyStateDescription('Comience evaluando la calidad de las cosechas.')
            ->emptyStateIcon('heroicon-o-star');
    }
}
