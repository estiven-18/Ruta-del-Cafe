<?php

namespace App\Filament\Resources\QualityEvaluations\Pages;

use App\Filament\Resources\QualityEvaluations\QualityEvaluationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateQualityEvaluation extends CreateRecord
{
    protected static string $resource = QualityEvaluationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['evaluated_by'] = Auth::id();
        $data['evaluation_date'] = now()->format('Y-m-d');
        return $data;
    }
}
