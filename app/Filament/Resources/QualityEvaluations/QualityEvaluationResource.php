<?php

namespace App\Filament\Resources\QualityEvaluations;

use App\Filament\Resources\QualityEvaluations\Pages\CreateQualityEvaluation;
use App\Filament\Resources\QualityEvaluations\Pages\EditQualityEvaluation;
use App\Filament\Resources\QualityEvaluations\Pages\ListQualityEvaluations;
use App\Filament\Resources\QualityEvaluations\Schemas\QualityEvaluationForm;
use App\Filament\Resources\QualityEvaluations\Tables\QualityEvaluationsTable;
use App\Models\QualityEvaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QualityEvaluationResource extends Resource
{
    protected static ?string $model = QualityEvaluation::class;

    protected static UnitEnum|string|null $navigationGroup = 'Catálogos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $navigationLabel = 'Evaluaciones de Calidad';
    protected static ?string $pluralLabel = 'Evaluaciones de Calidad';

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

    public static function getPages(): array
    {
        return [
            'index' => ListQualityEvaluations::route('/'),
            'create' => CreateQualityEvaluation::route('/create'),
            'edit' => EditQualityEvaluation::route('/{record}/edit'),
        ];
    }
}
