<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowUpTaskResource\Pages;
use App\Models\FollowUpTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FollowUpTaskResource extends Resource
{
    protected static ?string $model = FollowUpTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Follow-up Tasks';

    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('patient_id')->relationship('patient', 'last_name')->searchable()->preload(),
                Forms\Components\Select::make('patient_lead_id')->relationship('patientLead', 'phone')->searchable()->preload(),
                Forms\Components\Select::make('assignee_user_id')->relationship('assignee', 'name')->required()->searchable()->preload(),
                Forms\Components\TextInput::make('title')->required()->maxLength(150),
                Forms\Components\Select::make('task_type')->options(['call'=>'Call','message'=>'Message','checkin'=>'Check-in','custom'=>'Custom'])->required(),
                Forms\Components\Select::make('status')->options(['open'=>'Open','done'=>'Done','canceled'=>'Canceled'])->required(),
                Forms\Components\DateTimePicker::make('due_at')->required(),
                Forms\Components\DateTimePicker::make('completed_at'),
                Forms\Components\Textarea::make('description')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('due_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('assignee.name')->label('Assignee')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('task_type')->badge(),

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
            'index' => Pages\ManageFollowUpTasks::route('/'),
        ];
    }
}
