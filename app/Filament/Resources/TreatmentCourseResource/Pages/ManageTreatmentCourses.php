<?php

namespace App\Filament\Resources\TreatmentCourseResource\Pages;

use App\Filament\Resources\TreatmentCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTreatmentCourses extends ManageRecords
{
    protected static string $resource = TreatmentCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
