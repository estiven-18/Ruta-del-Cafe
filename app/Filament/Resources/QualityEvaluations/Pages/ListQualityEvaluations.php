<?php

namespace App\Filament\Resources\QualityEvaluations\Pages;

use App\Filament\Resources\QualityEvaluations\QualityEvaluationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQualityEvaluations extends ListRecords
{
    protected static string $resource = QualityEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
