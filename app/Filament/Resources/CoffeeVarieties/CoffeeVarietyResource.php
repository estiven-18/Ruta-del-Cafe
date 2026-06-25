<?php

namespace App\Filament\Resources\CoffeeVarieties;

use App\Filament\Resources\CoffeeVarieties\Pages\CreateCoffeeVariety;
use App\Filament\Resources\CoffeeVarieties\Pages\EditCoffeeVariety;
use App\Filament\Resources\CoffeeVarieties\Pages\ListCoffeeVarieties;
use App\Filament\Resources\CoffeeVarieties\Schemas\CoffeeVarietyForm;
use App\Filament\Resources\CoffeeVarieties\RelationManagers\CropsRelationManager;
use App\Filament\Resources\CoffeeVarieties\Tables\CoffeeVarietiesTable;
use App\Filament\Resources\CoffeeVarieties\Widgets\CoffeeVarietyStatsWidget;
use App\Models\CoffeeVariety;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CoffeeVarietyResource extends Resource
{
    protected static ?string $model = CoffeeVariety::class;

    protected static UnitEnum|string|null $navigationGroup = 'Registro';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $modelLabel = 'Variedad de Café';
    protected static ?string $pluralModelLabel = 'Variedades de Café';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Variedades de Café';
    protected static ?string $pluralLabel = 'Variedades de Café';

    public static function form(Schema $schema): Schema
    {
        return CoffeeVarietyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoffeeVarietiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CropsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CoffeeVarietyStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoffeeVarieties::route('/'),
            'create' => CreateCoffeeVariety::route('/create'),
            'edit' => EditCoffeeVariety::route('/{record}/edit'),
        ];
    }
}
