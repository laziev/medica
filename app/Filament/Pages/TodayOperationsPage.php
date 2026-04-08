<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard;

class TodayOperationsPage extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $navigationGroup = 'Today';
}
