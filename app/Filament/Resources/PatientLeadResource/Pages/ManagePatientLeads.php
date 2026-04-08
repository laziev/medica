<?php

namespace App\Filament\Resources\PatientLeadResource\Pages;

use App\Filament\Resources\PatientLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePatientLeads extends ManageRecords
{
    protected static string $resource = PatientLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
