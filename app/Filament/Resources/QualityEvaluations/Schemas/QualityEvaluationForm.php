<?php

namespace App\Filament\Resources\QualityEvaluations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class QualityEvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cosecha')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('harvest_id')
                            ->label('Cosecha')
                            ->relationship(
                                'harvest',
                                'id',
                                modifyQueryUsing: fn($query, $get, $record) => $query
                                    ->with(['crop.coffeeVariety', 'crop.farm' => fn($q) => $q->withTrashed()])
                                    ->whereDoesntHave('qualityEvaluations', fn($q) => $q->whereNull('deleted_at'))
                                    ->when($record, fn($q) => $q->orWhere('id', $record->harvest_id))
                            )
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                $farm = $record->crop?->farm?->name ?? '—';
                                return "Cosecha #{$record->id} - {$record->harvest_date} - {$farm}";
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationMessages([
                                'required' => 'Seleccione una cosecha para evaluar.',
                            ])
                            ->placeholder('Seleccione la cosecha a evaluar'),
                    ]),
                Section::make('Información de la Evaluación')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('evaluator_name')
                                    ->label('Evaluador')
                                    ->default(fn () => Auth::user()?->name)
                                    ->readOnly()
                                    ->helperText('Se asigna automáticamente del usuario logueado.'),
                                DatePicker::make('evaluation_date')
                                    ->label('Fecha de evaluación')
                                    ->default(fn () => now()->format('Y-m-d'))
                                    ->readOnly()
                                    ->native(false),
                            ]),
                    ]),
                Section::make('Puntajes de Catación')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(5)
                            ->schema([
                                TextInput::make('aroma_score')
                                    ->label('Aroma')
                                    ->numeric()
                                    ->step(0.1)
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set, Get $get) => static::recalculate($set, $get))
                                    ->suffix('/ 10')
                                    ->placeholder('0'),
                                TextInput::make('flavor_score')
                                    ->label('Sabor')
                                    ->numeric()
                                    ->step(0.1)
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set, Get $get) => static::recalculate($set, $get))
                                    ->suffix('/ 10')
                                    ->placeholder('0'),
                                TextInput::make('acidity_score')
                                    ->label('Acidez')
                                    ->numeric()
                                    ->step(0.1)
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set, Get $get) => static::recalculate($set, $get))
                                    ->suffix('/ 10')
                                    ->placeholder('0'),
                                TextInput::make('body_score')
                                    ->label('Cuerpo')
                                    ->numeric()
                                    ->step(0.1)
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set, Get $get) => static::recalculate($set, $get))
                                    ->suffix('/ 10')
                                    ->placeholder('0'),
                                TextInput::make('sweetness_score')
                                    ->label('Dulzor')
                                    ->numeric()
                                    ->step(0.1)
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set, Get $get) => static::recalculate($set, $get))
                                    ->suffix('/ 10')
                                    ->placeholder('0'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('final_score')
                                    ->label('Puntaje final')
                                    ->numeric()
                                    ->step(0.01)
                                    ->readOnly()
                                    ->required()
                                    ->helperText('Promedio automático de los 5 atributos.'),
                                TextInput::make('quality_grade')
                                    ->label('Calificación')
                                    ->readOnly()
                                    ->required()
                                    ->helperText('Especial (≥9), Alto (≥8), Medio (≥7), Bajo (<7).'),
                            ]),


                        Textarea::make('notes')
                            ->label('Notas')
                            ->rows(4)
                            ->placeholder('Aroma, sabor, cuerpo, acidez, balance...')
                            ->maxLength(65535)
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function recalculate(Set $set, Get $get): void
    {
        $scores = [
            (float) ($get('aroma_score') ?? 0),
            (float) ($get('flavor_score') ?? 0),
            (float) ($get('acidity_score') ?? 0),
            (float) ($get('body_score') ?? 0),
            (float) ($get('sweetness_score') ?? 0),
        ];

        $final = array_sum($scores) / count($scores);
        $set('final_score', round($final, 2));

        $grade = match (true) {
            $final >= 9 => 'especial',
            $final >= 8 => 'alto',
            $final >= 7 => 'medio',
            default => 'bajo',
        };
        $set('quality_grade', $grade);
    }
}
