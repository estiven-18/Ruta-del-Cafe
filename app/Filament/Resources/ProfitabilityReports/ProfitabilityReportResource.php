<?php

namespace App\Filament\Resources\ProfitabilityReports;

use App\Filament\Resources\ProfitabilityReports\Pages\ListProfitabilityReports;
use App\Filament\Resources\ProfitabilityReports\Tables\ProfitabilityReportsTable;
use App\Models\ProfitabilityReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProfitabilityReportResource extends Resource
{
    protected static ?string $model = ProfitabilityReport::class;

    protected static UnitEnum|string|null $navigationGroup = 'Costos y Evaluación';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Reportes de Rentabilidad';
    protected static ?string $pluralLabel = 'Reportes de Rentabilidad';

    public static function table(Table $table): Table
    {
        return ProfitabilityReportsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProfitabilityReports::route('/'),
        ];
    }
}
