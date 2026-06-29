<?php

namespace App\Filament\Resources\Crops;

use App\Filament\Resources\Crops\Pages\CreateCrop;
use App\Filament\Resources\Crops\Pages\EditCrop;
use App\Filament\Resources\Crops\Pages\ListCrops;
use App\Filament\Resources\Crops\Schemas\CropForm;
use App\Filament\Resources\Crops\Tables\CropsTable;
use App\Filament\Resources\Crops\Widgets\CropStatsWidget;
use App\Models\Crop;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class CropResource extends Resource
{
    protected static ?string $model = Crop::class;

    protected static UnitEnum|string|null $navigationGroup = 'Producción';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $modelLabel = 'Cultivo';
    protected static ?string $pluralModelLabel = 'Cultivos';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?int $navigationSort = 1;
    protected static bool $isGloballySearchable = true;

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        $farm = \App\Models\Farm::withTrashed()->find($record->farm_id);
        $variety = \App\Models\CoffeeVariety::find($record->coffee_variety_id);

        return 'Cultivo #' . str_pad($record->getKey(), 3, '0', STR_PAD_LEFT)
            . ' — ' . ($farm?->name ?? 'Sin finca') . ' / ' . ($variety?->name ?? 'Sin variedad');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Finca' => \App\Models\Farm::withTrashed()->find($record->farm_id)?->name ?? '—',
            'Variedad' => \App\Models\CoffeeVariety::find($record->coffee_variety_id)?->name ?? '—',
            'Estado' => ucfirst($record->status),
            'Área' => number_format($record->area_hectares, 2) . ' ha',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CropForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CropsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Crops\RelationManagers\HarvestsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CropStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCrops::route('/'),
            'create' => CreateCrop::route('/create'),
            'edit' => EditCrop::route('/{record}/edit'),
        ];
    }
}
