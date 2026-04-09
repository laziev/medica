<?php

namespace App\Filament\Resources\SignedDocumentResource\Pages;

use App\Filament\Resources\SignedDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSignedDocuments extends ManageRecords
{
    protected static string $resource = SignedDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
