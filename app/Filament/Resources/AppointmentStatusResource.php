<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentStatusResource\Pages;
use App\Models\AppointmentStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentStatusResource extends Resource
{
    protected static ?string $model = AppointmentStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Appointment Statuses';

    protected static ?string $navigationGroup = 'Scheduling Setup';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('location_id')->relationship('location', 'name')->searchable()->preload(),
                Forms\Components\TextInput::make('name')->required()->maxLength(80),
                Forms\Components\TextInput::make('slug')->required()->maxLength(80),
                Forms\Components\ColorPicker::make('color')->required(),
                Forms\Components\TextInput::make('sort_order')->numeric()->required(),
                Forms\Components\Toggle::make('is_default'),
                Forms\Components\Toggle::make('is_terminal'),
                Forms\Components\Toggle::make('is_active')->default(true),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('location.name')->label('Location'),

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAppointmentStatuses::route('/'),
        ];
    }
}
