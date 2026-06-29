<?php

namespace App\Filament\Resources\Producers;

use App\Filament\Resources\Producers\Pages\CreateProducer;
use App\Filament\Resources\Producers\Pages\EditProducer;
use App\Filament\Resources\Producers\Pages\ListProducers;
use App\Filament\Resources\Producers\Pages\ViewProducer;
use App\Filament\Resources\Producers\Schemas\ProducerForm;
use App\Filament\Resources\Producers\Tables\ProducersTable;
use App\Models\Producer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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
    protected static bool $isGloballySearchable = true;

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Documento' => $record->document_number ?? '—',
            'Teléfono' => $record->phone ?? '—',
            'Fincas' => $record->farms()->withTrashed()->count(),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['farms']);
    }

    public static function form(Schema $schema): Schema
    {
        return ProducerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\TextEntry::make('name')
                    ->label('Nombre'),
                \Filament\Infolists\Components\TextEntry::make('document_number')
                    ->label('Documento')
                    ->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('phone')
                    ->label('Teléfono')
                    ->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('farms')
                    ->label('Fincas Asociadas')
                    ->state(fn ($record) => $record->farms()->withTrashed()->pluck('name')->toArray())
                    ->badge()
                    ->placeholder('Sin fincas asociadas'),
                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return ProducersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Producers\RelationManagers\FarmsRelationManager::class,
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
