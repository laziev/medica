<?php

namespace App\Filament\Resources\TreatmentSessionResource\Pages;

use App\Filament\Resources\TreatmentSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTreatmentSessions extends ManageRecords
{
    protected static string $resource = TreatmentSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
