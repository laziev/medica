<?php

namespace App\Filament\Resources\MedicalQuestionnaireSubmissionResource\Pages;

use App\Filament\Resources\MedicalQuestionnaireSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMedicalQuestionnaireSubmissions extends ManageRecords
{
    protected static string $resource = MedicalQuestionnaireSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
