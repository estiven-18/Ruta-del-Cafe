<?php

namespace App\Filament\Resources\CostCategories;

use App\Filament\Resources\CostCategories\Pages\CreateCostCategory;
use App\Filament\Resources\CostCategories\Pages\EditCostCategory;
use App\Filament\Resources\CostCategories\Pages\ListCostCategories;
use App\Filament\Resources\CostCategories\Schemas\CostCategoryForm;
use App\Filament\Resources\CostCategories\Tables\CostCategoriesTable;
use App\Models\CostCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CostCategoryResource extends Resource
{
    protected static ?string $model = CostCategory::class;

    protected static UnitEnum|string|null $navigationGroup = 'Registro';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $modelLabel = 'Categoría de Costo';
    protected static ?string $pluralModelLabel = 'Categorías de Costos';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Categorías de Costos';
    protected static ?string $pluralLabel = 'Categorías de Costos';

    public static function form(Schema $schema): Schema
    {
        return CostCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CostCategoriesTable::configure($table);
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
            'index' => ListCostCategories::route('/'),
            'create' => CreateCostCategory::route('/create'),
            'edit' => EditCostCategory::route('/{record}/edit'),
        ];
    }
}
