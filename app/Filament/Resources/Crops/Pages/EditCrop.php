<?php

namespace App\Filament\Resources\Crops\Pages;

use App\Filament\Resources\Crops\CropResource;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditCrop extends EditRecord
{
    protected static string $resource = CropResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(fn($record) => $record->update(['status' => 'abandonado'])),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextEntry::make('farm.name')
                    ->label('Finca')
                    ->placeholder('—'),
                TextEntry::make('coffeeVariety.name')
                    ->label('Variedad')
                    ->placeholder('—'),
                TextEntry::make('planting_date')
                    ->label('Fecha de Siembra')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                TextEntry::make('estimated_harvest_date')
                    ->label('Cosecha Estimada')
                    ->date('d/m/Y')
                    ->placeholder('—'),
                TextEntry::make('area_hectares')
                    ->label('Área')
                    ->suffix(' ha')
                    ->placeholder('—'),
                TextEntry::make('plant_count')
                    ->label('Plantas')
                    ->placeholder('—'),
                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'activo' => 'success',
                        'cosechado' => 'info',
                        'abandonado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'activo' => 'Activo',
                        'cosechado' => 'Cosechado',
                        'abandonado' => 'Abandonado',
                        default => $state,
                    }),
                TextEntry::make('notes')
                    ->label('Notas')
                    ->placeholder('Sin notas')
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
