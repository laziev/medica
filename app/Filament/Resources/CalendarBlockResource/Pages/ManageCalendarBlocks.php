<?php

namespace App\Filament\Resources\CalendarBlockResource\Pages;

use App\Filament\Resources\CalendarBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCalendarBlocks extends ManageRecords
{
    protected static string $resource = CalendarBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
