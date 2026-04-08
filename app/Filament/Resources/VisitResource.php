<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitResource\Pages;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Visits';

    protected static ?string $navigationGroup = 'Clinical';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('patient_id')->relationship('patient', 'last_name')->required()->searchable()->preload(),
                Forms\Components\Select::make('appointment_id')->relationship('appointment', 'title')->searchable()->preload(),
                Forms\Components\Select::make('location_id')->relationship('location', 'name')->required()->searchable()->preload(),
                Forms\Components\Select::make('room_id')->relationship('room', 'name')->searchable()->preload(),
                Forms\Components\Select::make('doctor_user_id')->relationship('doctor', 'name')->required()->searchable()->preload(),
                Forms\Components\DateTimePicker::make('started_at')->required(),
                Forms\Components\DateTimePicker::make('ended_at'),
                Forms\Components\Select::make('visit_type')->options(['consultation'=>'Consultation','procedure'=>'Procedure','control'=>'Control','mixed'=>'Mixed'])->required(),
                Forms\Components\Select::make('status')->options(['open'=>'Open','finalized'=>'Finalized'])->required(),
                Forms\Components\Textarea::make('clinical_note')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('started_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('patient.last_name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('doctor.name')->label('Doctor')->sortable(),
                Tables\Columns\TextColumn::make('visit_type')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),

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
        return auth()->user()?->can('medical.visits.view') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }


    public static function getRelations(): array
    {
        return [
            VisitResource\RelationManagers\TreatmentSessionsRelationManager::class,
            VisitResource\RelationManagers\VisitSignedDocumentsRelationManager::class,
            VisitResource\RelationManagers\VisitQuestionnaireSubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVisits::route('/'),
        ];
    }
}
