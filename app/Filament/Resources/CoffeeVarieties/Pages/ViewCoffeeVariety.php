<?php

namespace App\Filament\Resources\CoffeeVarieties\Pages;

use App\Filament\Resources\CoffeeVarieties\CoffeeVarietyResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewCoffeeVariety extends ViewRecord
{
    protected static string $resource = CoffeeVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Editar')
                ->url(fn () => CoffeeVarietyResource::getUrl('edit', ['record' => $this->record])),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextEntry::make('name')
                    ->label('Nombre')
                    ->placeholder('—'),
                TextEntry::make('scientific_name')
                    ->label('Nombre Científico')
                    ->placeholder('—'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->placeholder('Sin descripción'),
                TextEntry::make('typical_maturity_months')
                    ->label('Meses de Maduración')
                    ->suffix(' meses')
                    ->placeholder('—'),
                TextEntry::make('is_resistant')
                    ->label('Resistente')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Sí' : 'No')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
                TextEntry::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),
            ]);
    }
}
