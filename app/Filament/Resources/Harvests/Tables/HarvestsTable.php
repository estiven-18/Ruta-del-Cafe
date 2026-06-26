<?php

namespace App\Filament\Resources\Harvests\Tables;

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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HarvestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withTrashed())
            ->columns([
                //numero de cosecha
                TextColumn::make('id')
                    ->label('Cosecha')
                    ->numeric()
                    ->prefix('#')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('harvest_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->weight('bold')
                    ->description(function ($record) {
                        $crop = $record->crop;
                        if (! $crop) return '—';
                        $farm = $crop->farm()?->withTrashed()->first();
                        return $farm?->name ?? '—';
                    }),
                TextColumn::make('crop.coffeeVariety.name')
                    ->label('Variedad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gross_weight_kg')
                    ->label('Peso Bruto')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('defective_weight_kg')
                    ->label('Defectuoso')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('net_weight_kg')
                    ->label('Peso Neto')
                    ->numeric(2)
                    ->suffix(' kg')
                    ->sortable()
                    ->weight('bold')
                    ->summarize([
                        Sum::make()->label('Total')->numeric(2)->suffix(' kg'),
                    ]),
                TextColumn::make('sale_price_per_kg')
                    ->label('Precio/kg')
                    ->money('Cop')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_income')
                    ->label('Ingresos')
                    ->money('Cop')
                    ->sortable()
                    ->color('success')
                    ->weight('bold')
                    ->summarize([
                        Sum::make()->label('Total')->money('Cop'),
                    ]),
                TextColumn::make('qualityEvaluations.quality_grade')
                    ->label('Calidad')
                    ->getStateUsing(fn ($record) => $record->qualityEvaluations->first()?->quality_grade ?? 'Sin evaluar')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'especial' => 'purple',
                        'alto' => 'success',
                        'medio' => 'warning',
                        'bajo' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'especial' => 'Specialty',
                        'alto' => 'Premium',
                        'medio' => 'Commercial',
                        'bajo' => 'Below Grade',
                        default => 'Sin evaluar',
                    }),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(fn ($record) => $record->trashed() ? ['bg-danger-50', 'dark:bg-danger-950'] : [])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('farm')
                    ->label('Finca')
                    ->relationship('crop.farm', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todas'),
                SelectFilter::make('variety')
                    ->label('Variedad')
                    ->relationship('crop.coffeeVariety', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todas'),
                Filter::make('harvest_date')
                    ->label('Fecha de Cosecha')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('Desde'),
                        \Filament\Forms\Components\DatePicker::make('to')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $v) => $q->where('harvest_date', '>=', $v))
                            ->when($data['to'] ?? null, fn ($q, $v) => $q->where('harvest_date', '<=', $v));
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Ver Detalles')
                        ->modalHeading('Detalles de la Cosecha')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Cerrar')
                        ->hidden(fn ($record) => $record->trashed())
                        ->infolist(fn ($record): array => [
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('harvest_date')
                                        ->label('Fecha de Cosecha')
                                        ->date('d/m/Y')
                                        ->placeholder('—'),
                                    TextEntry::make('crop.farm.name')
                                        ->label('Finca')
                                        ->placeholder('—'),
                                    TextEntry::make('crop.coffeeVariety.name')
                                        ->label('Variedad')
                                        ->placeholder('—'),
                                    TextEntry::make('crop.status')
                                        ->label('Estado Cultivo')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'activo' => 'success',
                                            'cosechado' => 'info',
                                            'abandonado' => 'danger',
                                            default => 'gray',
                                        }),
                                    TextEntry::make('gross_weight_kg')
                                        ->label('Peso Bruto')
                                        ->suffix(' kg')
                                        ->placeholder('—'),
                                    TextEntry::make('defective_weight_kg')
                                        ->label('Defectuoso')
                                        ->suffix(' kg')
                                        ->placeholder('—'),
                                    TextEntry::make('net_weight_kg')
                                        ->label('Peso Neto')
                                        ->suffix(' kg')
                                        ->placeholder('—'),
                                    TextEntry::make('sale_price_per_kg')
                                        ->label('Precio/kg')
                                        ->money('Cop')
                                        ->placeholder('—'),
                                    TextEntry::make('total_income')
                                        ->label('Ingresos')
                                        ->money('Cop')
                                        ->placeholder('—'),
                                    TextEntry::make('qualityEvaluations.quality_grade')
                                        ->label('Calidad')
                                        ->getStateUsing(fn ($record) => $record->qualityEvaluations->first()?->quality_grade ?? 'Sin evaluar')
                                        ->badge()
                                        ->color(fn (?string $state): string => match ($state) {
                                            'especial' => 'purple',
                                            'alto' => 'success',
                                            'medio' => 'warning',
                                            'bajo' => 'danger',
                                            default => 'gray',
                                        })
                                        ->formatStateUsing(fn (?string $state): string => match ($state) {
                                            'especial' => 'Specialty',
                                            'alto' => 'Premium',
                                            'medio' => 'Commercial',
                                            'bajo' => 'Below Grade',
                                            default => 'Sin evaluar',
                                        }),
                                ]),
                            TextEntry::make('notes')
                                ->label('Notas')
                                ->placeholder('Sin notas')
                                ->columnSpanFull(),
                        ]),
                    EditAction::make()->label('Editar')
                        ->hidden(fn ($record) => $record->trashed()),
                    \Filament\Actions\Action::make('evaluate_quality')
                        ->label('Evaluar Calidad')
                        ->icon('heroicon-o-star')
                        ->color('info')
                        ->url(fn ($record) => \App\Filament\Resources\QualityEvaluations\QualityEvaluationResource::getUrl('create'))
                        ->visible(fn ($record) => $record->qualityEvaluations()->count() === 0),
                    DeleteAction::make()->label('Eliminar')
                        ->hidden(fn ($record) => $record->trashed()),
                    RestoreAction::make()->label('Restaurar')
                        ->visible(fn ($record) => $record->trashed()),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Eliminar'),
                    RestoreBulkAction::make()->label('Restaurar'),
                ]),
            ])
            ->defaultSort('harvest_date', 'desc')
            ->emptyStateHeading('Sin cosechas registradas')
            ->emptyStateDescription('Las cosechas se generan a partir de los cultivos activos.')
            ->emptyStateIcon('heroicon-o-cube-transparent');
    }
}
