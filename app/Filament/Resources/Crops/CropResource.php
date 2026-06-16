<?php

namespace App\Filament\Resources\Crops;

use App\Filament\Resources\Crops\Pages\CreateCrop;
use App\Filament\Resources\Crops\Pages\EditCrop;
use App\Filament\Resources\Crops\Pages\ListCrops;
use App\Filament\Resources\Crops\Schemas\CropForm;
use App\Filament\Resources\Crops\Tables\CropsTable;
use App\Models\Crop;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CropResource extends Resource
{
    protected static ?string $model = Crop::class;

    protected static UnitEnum|string|null $navigationGroup = 'Catálogos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $navigationLabel = 'Cultivos';
    protected static ?string $pluralLabel = 'Cultivos';

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
            //
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
