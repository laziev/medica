<?php

namespace App\Filament\Resources\ServiceCatalogItemResource\Pages;

use App\Filament\Resources\ServiceCatalogItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageServiceCatalogItems extends ManageRecords
{
    protected static string $resource = ServiceCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
