<?php

namespace App\Filament\Resources\QualityEvaluations\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
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
                        'especial' => 'success',
                        'alto' => 'info',
                        'medio' => 'warning',
                        'bajo' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'especial' => 'Especial',
                        'alto' => 'Alto',
                        'medio' => 'Medio',
                        'bajo' => 'Bajo',
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
                        'especial' => 'Especial',
                        'alto' => 'Alto',
                        'medio' => 'Medio',
                        'bajo' => 'Bajo',
                    ])
                    ->placeholder('Todas'),
            ])
            ->recordActions([
                ActionGroup::make([
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
