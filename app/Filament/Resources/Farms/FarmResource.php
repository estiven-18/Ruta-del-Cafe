<?php

namespace App\Filament\Resources\Farms;

use App\Filament\Resources\Farms\Pages\CreateFarm;
use App\Filament\Resources\Farms\Pages\EditFarm;
use App\Filament\Resources\Farms\Pages\ListFarms;
use App\Filament\Resources\Farms\Schemas\FarmForm;
use App\Filament\Resources\Farms\Tables\FarmsTable;
use App\Models\Farm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FarmResource extends Resource
{
    protected static ?string $model = Farm::class;

    protected static UnitEnum|string|null $navigationGroup = 'Registro';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static ?string $modelLabel = 'Finca';
    protected static ?string $pluralModelLabel = 'Fincas';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return FarmForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\TextEntry::make('name')
                    ->label('Nombre'),
                \Filament\Infolists\Components\TextEntry::make('location')
                    ->label('Ubicación')
                    ->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('total_area_hectares')
                    ->label('Área Total')
                    ->suffix(' ha'),
                \Filament\Infolists\Components\TextEntry::make('producers')
                    ->label('Productores')
                    ->state(fn ($record) => $record->producers()->withTrashed()->pluck('name')->toArray())
                    ->badge()
                    ->placeholder('Sin productores'),
                \Filament\Infolists\Components\TextEntry::make('notes')
                    ->label('Notas')
                    ->placeholder('Sin notas'),
                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return FarmsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Farms\RelationManagers\ProducersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarms::route('/'),
            'create' => CreateFarm::route('/create'),
            'edit' => EditFarm::route('/{record}/edit'),
        ];
    }
}
