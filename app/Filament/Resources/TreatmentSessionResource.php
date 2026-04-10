<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentSessionResource\Pages;
use App\Models\TreatmentSession;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TreatmentSessionResource extends Resource
{
    protected static ?string $model = TreatmentSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Treatment Sessions';

    protected static ?string $navigationGroup = 'Clinical';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Forms\Components\Select::make('treatment_course_id')->relationship('course', 'title')->required()->searchable()->preload(),
                Forms\Components\Select::make('visit_id')->relationship('visit', 'id')->required()->searchable()->preload(),
                Forms\Components\Select::make('parent_session_id')->relationship('parentSession', 'id')->searchable()->preload(),
                Forms\Components\Select::make('session_kind')->options(['primary'=>'Primary','correction'=>'Correction','standalone'=>'Standalone'])->required(),
                Forms\Components\Select::make('service_catalog_item_id')->relationship('serviceCatalogItem', 'name')->searchable()->preload(),
                Forms\Components\DateTimePicker::make('performed_at')->required(),
                Forms\Components\TextInput::make('agreed_price')->numeric(),
                Forms\Components\Textarea::make('comments')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('performed_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('course.title')->label('Course')->searchable(),
                Tables\Columns\TextColumn::make('session_kind')->badge(),
                Tables\Columns\TextColumn::make('performer.name')->label('Doctor'),
                Tables\Columns\TextColumn::make('agreed_price')->money('USD'),

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


    public static function canViewAny(): bool
    {
        return auth()->user()?->can('medical.treatment_sessions.view') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }


    public static function getRelations(): array
    {
        return [
            TreatmentSessionResource\RelationManagers\ProductUsagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTreatmentSessions::route('/'),
        ];
    }
}
