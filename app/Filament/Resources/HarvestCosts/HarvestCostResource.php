<?php

namespace App\Filament\Resources\HarvestCosts;

use App\Filament\Resources\HarvestCosts\Pages\CreateHarvestCost;
use App\Filament\Resources\HarvestCosts\Pages\EditHarvestCost;
use App\Filament\Resources\HarvestCosts\Pages\ListHarvestCosts;
use App\Filament\Resources\HarvestCosts\Schemas\HarvestCostForm;
use App\Filament\Resources\HarvestCosts\Tables\HarvestCostsTable;
use App\Models\HarvestCost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HarvestCostResource extends Resource
{
    protected static ?string $model = HarvestCost::class;

    protected static UnitEnum|string|null $navigationGroup = 'Costos y Evaluación';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $modelLabel = 'Costo de Cosecha';
    protected static ?string $pluralModelLabel = 'Costos de Cosecha';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Costos de Cosecha';
    protected static ?string $pluralLabel = 'Costos de Cosecha';

    public static function form(Schema $schema): Schema
    {
        return HarvestCostForm::configureForCreate($schema);
    }

    public static function table(Table $table): Table
    {
        return HarvestCostsTable::configure($table);
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
            'index' => ListHarvestCosts::route('/'),
            'create' => CreateHarvestCost::route('/create'),
            'edit' => EditHarvestCost::route('/{record}/edit'),
        ];
    }
}
