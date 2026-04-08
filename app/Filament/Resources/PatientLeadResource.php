<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientLeadResource\Pages;
use App\Models\PatientLead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PatientLeadResource extends Resource
{
    protected static ?string $model = PatientLead::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Leads';

    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('first_name')->maxLength(120),
                Forms\Components\TextInput::make('last_name')->maxLength(120),
                Forms\Components\TextInput::make('phone')->required()->maxLength(30),
                Forms\Components\TextInput::make('instagram')->maxLength(120),
                Forms\Components\TextInput::make('source')->maxLength(60),
                Forms\Components\Select::make('status')->options(['new' => 'New', 'active' => 'Active', 'closed' => 'Closed'])->required(),
                Forms\Components\Textarea::make('notes')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('first_name')->searchable(),
                Tables\Columns\TextColumn::make('last_name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),

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
            'index' => Pages\ManagePatientLeads::route('/'),
        ];
    }
}
