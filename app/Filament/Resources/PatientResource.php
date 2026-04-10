<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Patients';

    protected static ?string $navigationGroup = 'CRM';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Forms\Components\TextInput::make('first_name')->required()->maxLength(120),
                Forms\Components\TextInput::make('last_name')->required()->maxLength(120),
                Forms\Components\TextInput::make('phone')->required()->maxLength(30),
                Forms\Components\TextInput::make('email')->email()->maxLength(190),
                Forms\Components\TextInput::make('instagram')->maxLength(120),
                Forms\Components\DatePicker::make('date_of_birth'),
                Forms\Components\Select::make('sex')->options(['female'=>'Female','male'=>'Male','other'=>'Other']),
                Forms\Components\Textarea::make('general_notes')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')->date()->sortable(),
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
            PatientResource\RelationManagers\AppointmentsRelationManager::class,
            PatientResource\RelationManagers\VisitsRelationManager::class,
            PatientResource\RelationManagers\TreatmentCoursesRelationManager::class,
            PatientResource\RelationManagers\QuestionnaireSubmissionsRelationManager::class,
            PatientResource\RelationManagers\SignedDocumentsRelationManager::class,
            PatientResource\RelationManagers\FollowUpTasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePatients::route('/'),
        ];
    }
}
