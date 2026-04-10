<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentCourseResource\Pages;
use App\Models\TreatmentCourse;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TreatmentCourseResource extends Resource
{
    protected static ?string $model = TreatmentCourse::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Treatment Courses';

    protected static ?string $navigationGroup = 'Clinical';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Forms\Components\Select::make('patient_id')->relationship('patient', 'last_name')->required()->searchable()->preload(),
                Forms\Components\TextInput::make('title')->required()->maxLength(150),
                Forms\Components\Select::make('status')->options(['active'=>'Active','closed'=>'Closed'])->required(),
                Forms\Components\DateTimePicker::make('opened_at')->required(),
                Forms\Components\DateTimePicker::make('closed_at'),
                Forms\Components\Textarea::make('goal')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('patient.last_name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('opened_at')->dateTime()->sortable(),

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
        return auth()->user()?->can('medical.treatment_courses.view') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }


    public static function getRelations(): array
    {
        return [
            TreatmentCourseResource\RelationManagers\CourseSessionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTreatmentCourses::route('/'),
        ];
    }
}
