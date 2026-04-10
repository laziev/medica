<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicalQuestionnaireSubmissionResource\Pages;
use App\Models\MedicalQuestionnaireSubmission;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MedicalQuestionnaireSubmissionResource extends Resource
{
    protected static ?string $model = MedicalQuestionnaireSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Questionnaire Submissions';

    protected static ?string $navigationGroup = 'Clinical';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Forms\Components\Select::make('patient_id')->relationship('patient', 'last_name')->required()->searchable()->preload(),
                Forms\Components\Select::make('template_id')->relationship('template', 'name')->required()->searchable()->preload(),
                Forms\Components\Select::make('template_version_id')->relationship('templateVersion', 'id')->required()->searchable()->preload(),
                Forms\Components\TextInput::make('template_version_no')->numeric()->required(),
                Forms\Components\TextInput::make('locale_used')->required()->maxLength(10),
                Forms\Components\DateTimePicker::make('submitted_at')->required(),
                Forms\Components\Select::make('source')->options(['staff'=>'Staff','qr_self_fill'=>'QR Self-fill'])->required(),
                Forms\Components\KeyValue::make('answers_json')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('submitted_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('patient.last_name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('template.name')->label('Template'),
                Tables\Columns\TextColumn::make('locale_used')->badge(),
                Tables\Columns\TextColumn::make('source')->badge(),

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
        return auth()->user()?->can('medical.questionnaires.view') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMedicalQuestionnaireSubmissions::route('/'),
        ];
    }
}
