<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignedDocumentResource\Pages;
use App\Models\SignedDocument;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SignedDocumentResource extends Resource
{
    protected static ?string $model = SignedDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Signed Documents';

    protected static ?string $navigationGroup = 'Clinical';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Forms\Components\Select::make('patient_id')->relationship('patient', 'last_name')->required()->searchable()->preload(),
                Forms\Components\Select::make('document_template_id')->relationship('documentTemplate', 'name')->required()->searchable()->preload(),
                Forms\Components\Select::make('document_template_version_id')->relationship('documentTemplateVersion', 'id')->required()->searchable()->preload(),
                Forms\Components\TextInput::make('template_version_no')->numeric()->required(),
                Forms\Components\TextInput::make('locale_used')->required()->maxLength(10),
                Forms\Components\DateTimePicker::make('signed_at')->required(),
                Forms\Components\TextInput::make('signed_by_name')->required()->maxLength(190),
                Forms\Components\Select::make('status')->options(['signed'=>'Signed','voided'=>'Voided'])->required(),
                Forms\Components\KeyValue::make('document_snapshot_json')->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('signed_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('patient.last_name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('documentTemplate.name')->label('Template'),
                Tables\Columns\TextColumn::make('locale_used')->badge(),
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
        return auth()->user()?->can('medical.signed_documents.view') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSignedDocuments::route('/'),
        ];
    }
}
