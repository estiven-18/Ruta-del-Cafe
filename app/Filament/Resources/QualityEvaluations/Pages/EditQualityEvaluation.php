<?php

namespace App\Filament\Resources\QualityEvaluations\Pages;

use App\Filament\Resources\QualityEvaluations\QualityEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQualityEvaluation extends EditRecord
{
    protected static string $resource = QualityEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = static::getResource()::getModel()::find($data['id']);

        if ($record) {
            $data['evaluator_name'] = $record->evaluator?->name;
            $data['evaluation_date'] = $record->evaluation_date;
        }

        return $data;
    }
}
