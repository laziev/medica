<?php

namespace App\Filament\Resources\FollowUpTaskResource\Pages;

use App\Filament\Resources\FollowUpTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFollowUpTasks extends ManageRecords
{
    protected static string $resource = FollowUpTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
