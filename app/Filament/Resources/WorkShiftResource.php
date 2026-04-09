<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkShiftResource\Pages;
use App\Models\WorkShift;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkShiftResource extends Resource
{
    protected static ?string $model = WorkShift::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Work Shifts';

    protected static ?string $navigationGroup = 'Scheduling Setup';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('location_id')->relationship('location', 'name')->required()->searchable()->preload(),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required()->searchable()->preload(),
                Forms\Components\DateTimePicker::make('starts_at')->required(),
                Forms\Components\DateTimePicker::make('ends_at')->required(),
                Forms\Components\Toggle::make('is_recurring')->default(false),
                Forms\Components\TextInput::make('recurrence_rule')->maxLength(255),
                Forms\Components\TextInput::make('note')->maxLength(255),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('location.name')->label('Location')->sortable(),
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('ends_at')->dateTime(),
                Tables\Columns\IconColumn::make('is_recurring')->boolean(),

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
            'index' => Pages\ManageWorkShifts::route('/'),
        ];
    }
}
