<?php

namespace App\Filament\Resources\Producers;

use App\Filament\Resources\Producers\Pages\CreateProducer;
use App\Filament\Resources\Producers\Pages\EditProducer;
use App\Filament\Resources\Producers\Pages\ListProducers;
use App\Filament\Resources\Producers\Schemas\ProducerForm;
use App\Filament\Resources\Producers\Tables\ProducersTable;
use App\Models\Producer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProducerResource extends Resource
{
    protected static ?string $model = Producer::class;

    protected static UnitEnum|string|null $navigationGroup = 'Registro';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $modelLabel = 'Productor';
    protected static ?string $pluralModelLabel = 'Productores';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Productores';
    protected static ?string $pluralLabel = 'Productores';

    public static function form(Schema $schema): Schema
    {
        return ProducerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProducersTable::configure($table);
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
            'index' => ListProducers::route('/'),
            'create' => CreateProducer::route('/create'),
            'edit' => EditProducer::route('/{record}/edit'),
        ];
    }
}
