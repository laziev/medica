<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarBlockResource\Pages;
use App\Models\CalendarBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CalendarBlockResource extends Resource
{
    protected static ?string $model = CalendarBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Calendar Blocks';

    protected static ?string $navigationGroup = 'Scheduling Setup';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('location_id')->relationship('location', 'name')->required()->searchable()->preload(),
                Forms\Components\Select::make('room_id')->relationship('room', 'name')->searchable()->preload(),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload(),
                Forms\Components\TextInput::make('title')->required()->maxLength(120),
                Forms\Components\Select::make('block_type')->options(['unavailable'=>'Unavailable','lunch'=>'Lunch','maintenance'=>'Maintenance','placeholder'=>'Placeholder'])->required(),
                Forms\Components\DateTimePicker::make('starts_at')->required(),
                Forms\Components\DateTimePicker::make('ends_at')->required(),
                Forms\Components\Toggle::make('is_hard_block')->default(true),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('block_type')->badge(),
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('ends_at')->dateTime(),
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
            'index' => Pages\ManageCalendarBlocks::route('/'),
        ];
    }
}
