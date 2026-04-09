<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Locations';

    protected static ?string $navigationGroup = 'Organization';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')->required()->maxLength(150),
                Forms\Components\TextInput::make('code')->required()->maxLength(30),
                Forms\Components\TextInput::make('address_line')->maxLength(255),
                Forms\Components\TextInput::make('city')->maxLength(120),
                Forms\Components\TextInput::make('country_code')->maxLength(2),
                Forms\Components\TextInput::make('phone')->maxLength(30),
                Forms\Components\Toggle::make('is_active')->default(true),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('city')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),

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


    public static function getRelations(): array
    {
        return [
            LocationResource\RelationManagers\RoomsRelationManager::class,
            LocationResource\RelationManagers\LocationShiftsRelationManager::class,
            LocationResource\RelationManagers\LocationBlocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLocations::route('/'),
        ];
    }
}
