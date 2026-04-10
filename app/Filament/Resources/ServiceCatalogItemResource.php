<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCatalogItemResource\Pages;
use App\Models\ServiceCatalogItem;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceCatalogItemResource extends Resource
{
    protected static ?string $model = ServiceCatalogItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Service Catalog';

    protected static ?string $navigationGroup = 'Scheduling Setup';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Forms\Components\TextInput::make('name')->required()->maxLength(150),
                Forms\Components\TextInput::make('category')->maxLength(80),
                Forms\Components\TextInput::make('default_duration_minutes')->numeric(),
                Forms\Components\TextInput::make('default_price')->numeric(),
                Forms\Components\TextInput::make('currency_code')->required()->maxLength(3),
                Forms\Components\Toggle::make('is_active')->default(true),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('default_duration_minutes')->label('Minutes'),
                Tables\Columns\TextColumn::make('default_price')->money('USD'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageServiceCatalogItems::route('/'),
        ];
    }
}
