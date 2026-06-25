<?php

namespace App\Filament\Resources\CostCategories\Pages;

use App\Filament\Resources\CostCategories\CostCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListCostCategories extends ListRecords
{
    protected static string $resource = CostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Categoría de Costo')
                ->modalHeading('Nueva Categoría de Costo')
                ->modalSubmitActionLabel('Crear'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\CostCategories\Widgets\CostCategoryStatsWidget::class,
        ];
    }
}
