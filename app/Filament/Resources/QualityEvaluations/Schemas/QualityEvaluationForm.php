<?php

namespace App\Filament\Resources\QualityEvaluations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class QualityEvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('harvest_id')
                    ->label('Cosecha')
                    ->relationship('harvest', 'id', modifyQueryUsing: fn ($query, $get, $record) => $query
                        ->whereDoesntHave('qualityEvaluations', fn ($q) => $q->whereNull('deleted_at'))
                        ->when($record, fn ($q) => $q->orWhere('id', $record->harvest_id))
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Cosecha del {$record->harvest_date} - {$record->crop->farm->name}")
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('evaluator_name')
                    ->label('Evaluado por')
                    ->default(Auth::user()?->name)
                    ->dehydrated(false)
                    ->readOnly(),
                DatePicker::make('evaluation_date')
                    ->label('Fecha de evaluación')
                    ->required()
                    ->default(now())
                    ->readOnly(),
                TextInput::make('aroma_score')
                    ->label('Aroma (0-10)')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(0)
                    ->maxValue(10)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('flavor_score')
                    ->label('Sabor (0-10)')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(0)
                    ->maxValue(10)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('acidity_score')
                    ->label('Acidez (0-10)')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(0)
                    ->maxValue(10)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('body_score')
                    ->label('Cuerpo (0-10)')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(0)
                    ->maxValue(10)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('sweetness_score')
                    ->label('Dulzor (0-10)')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(0)
                    ->maxValue(10)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => static::recalculate($set, $get)),
                TextInput::make('final_score')
                    ->label('Puntaje final')
                    ->numeric()
                    ->step(0.01)
                    ->readOnly()
                    ->required(),
                TextInput::make('quality_grade')
                    ->label('Calificación')
                    ->readOnly()
                    ->required(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->maxLength(65535)
                    ->default(null)
                    ->columnSpanFull(),
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
