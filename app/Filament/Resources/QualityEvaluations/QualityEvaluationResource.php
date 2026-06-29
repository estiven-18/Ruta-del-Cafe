<?php

namespace App\Filament\Resources\QualityEvaluations;

use App\Filament\Resources\QualityEvaluations\Pages\CreateQualityEvaluation;
use App\Filament\Resources\QualityEvaluations\Pages\EditQualityEvaluation;
use App\Filament\Resources\QualityEvaluations\Pages\ListQualityEvaluations;
use App\Filament\Resources\QualityEvaluations\Schemas\QualityEvaluationForm;
use App\Filament\Resources\QualityEvaluations\Tables\QualityEvaluationsTable;
use App\Filament\Resources\QualityEvaluations\Widgets\QualityEvaluationStatsWidget;
use App\Models\QualityEvaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class QualityEvaluationResource extends Resource
{
    protected static ?string $model = QualityEvaluation::class;

    protected static UnitEnum|string|null $navigationGroup = 'Costos y Evaluación';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $modelLabel = 'Evaluación de Calidad';
    protected static ?string $pluralModelLabel = 'Evaluaciones de Calidad';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Evaluaciones de Calidad';
    protected static ?string $pluralLabel = 'Evaluaciones de Calidad';
    protected static bool $isGloballySearchable = true;

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        $harvest = \App\Models\Harvest::withTrashed()->find($record->harvest_id);

        return 'Evaluación #' . str_pad($record->getKey(), 3, '0', STR_PAD_LEFT)
            . ' — Cosecha #' . str_pad($record->harvest_id, 3, '0', STR_PAD_LEFT);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $gradeLabels = ['especial' => 'Especial', 'alto' => 'Premium', 'medio' => 'Comercial', 'bajo' => 'Bajo Grado'];

        return [
            'Puntaje' => number_format($record->final_score, 1),
            'Calidad' => $gradeLabels[$record->quality_grade] ?? $record->quality_grade,
            'Evaluador' => $record->evaluator ?? '—',
            'Fecha' => $record->evaluation_date?->format('d/m/Y') ?? '—',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return QualityEvaluationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QualityEvaluationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            QualityEvaluationStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQualityEvaluations::route('/'),
            'create' => CreateQualityEvaluation::route('/create'),
            'edit' => EditQualityEvaluation::route('/{record}/edit'),
        ];
    }
}
