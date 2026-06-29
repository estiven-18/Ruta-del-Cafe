<?php

namespace App\Filament\Resources\Harvests;

use App\Filament\Resources\Harvests\Pages\CreateHarvest;
use App\Filament\Resources\Harvests\Pages\EditHarvest;
use App\Filament\Resources\Harvests\Pages\ListHarvests;
use App\Filament\Resources\Harvests\Schemas\HarvestForm;
use App\Filament\Resources\Harvests\Tables\HarvestsTable;
use App\Filament\Resources\Harvests\Widgets\HarvestStatsWidget;
use App\Models\Harvest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class HarvestResource extends Resource
{
    protected static ?string $model = Harvest::class;

    protected static UnitEnum|string|null $navigationGroup = 'Producción';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTrendingUp;

    protected static ?string $modelLabel = 'Cosecha';
    protected static ?string $pluralModelLabel = 'Cosechas';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?int $navigationSort = 3;
    protected static bool $isGloballySearchable = true;

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        $crop = \App\Models\Crop::withTrashed()->find($record->crop_id);
        $farm = $crop ? \App\Models\Farm::withTrashed()->find($crop->farm_id) : null;

        return 'Cosecha #' . str_pad($record->getKey(), 3, '0', STR_PAD_LEFT)
            . ' — ' . ($farm?->name ?? 'Sin finca');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Fecha' => $record->harvest_date?->format('d/m/Y') ?? '—',
            'Producción' => number_format($record->net_weight_kg, 0) . ' kg',
            'Ingresos' => '$' . number_format($record->total_income, 0, ',', '.'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return HarvestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HarvestsTable::configure($table);
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
            HarvestStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHarvests::route('/'),
            'create' => CreateHarvest::route('/create'),
            'edit' => EditHarvest::route('/{record}/edit'),
        ];
    }
}
