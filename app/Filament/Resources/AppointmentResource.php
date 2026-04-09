<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\ClinicalFile;
use App\Models\PatientLead;
use App\Models\TreatmentCourse;
use App\Models\TreatmentSession;
use App\Models\TreatmentSessionProductUsage;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Appointments';

    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking')
                    ->schema([
                        Forms\Components\Select::make('patient_id')
                            ->relationship('patient', 'last_name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('patient_lead_id')
                            ->relationship('patientLead', 'phone')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('first_name')->maxLength(120),
                                Forms\Components\TextInput::make('last_name')->maxLength(120),
                                Forms\Components\TextInput::make('phone')->required()->maxLength(30),
                                Forms\Components\TextInput::make('instagram')->maxLength(120),
                                Forms\Components\Select::make('status')->options([
                                    'new' => 'New',
                                    'active' => 'Active',
                                ])->default('new')->required(),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $data['created_by'] = auth()->id();

                                return PatientLead::query()->create($data)->id;
                            }),
                        Forms\Components\Select::make('location_id')->relationship('location', 'name')->required()->searchable()->preload(),
                        Forms\Components\Select::make('room_id')->relationship('room', 'name')->required()->searchable()->preload(),
                        Forms\Components\Select::make('doctor_user_id')->relationship('doctor', 'name')->required()->searchable()->preload(),
                        Forms\Components\Select::make('status_id')->relationship('status', 'name')->required()->searchable()->preload(),
                        Forms\Components\TextInput::make('title')->required()->maxLength(150),
                        Forms\Components\DateTimePicker::make('starts_at')->required(),
                        Forms\Components\DateTimePicker::make('ends_at')->required(),
                        Forms\Components\TextInput::make('duration_minutes')->numeric()->required(),
                        Forms\Components\TextInput::make('agreed_price')->numeric(),
                        Forms\Components\Textarea::make('internal_comment')->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('patient.last_name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('patientLead.phone')->label('Lead')->searchable(),
                Tables\Columns\TextColumn::make('doctor.name')->label('Doctor')->sortable(),
                Tables\Columns\TextColumn::make('status.name')->badge(),
                Tables\Columns\TextColumn::make('room.name')->label('Room'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('mark_arrived')
                    ->label('Arrived')
                    ->icon('heroicon-o-user-circle')
                    ->visible(fn () => auth()->user()?->can('appointments.update') ?? true)
                    ->action(fn (Appointment $record) => static::applyStatusIfExists($record, 'arrived')),
                Tables\Actions\Action::make('mark_in_progress')
                    ->label('In progress')
                    ->icon('heroicon-o-clock')
                    ->visible(fn () => auth()->user()?->can('appointments.update') ?? true)
                    ->action(fn (Appointment $record) => static::applyStatusIfExists($record, 'in_progress')),
                Tables\Actions\Action::make('start_visit')
                    ->label('Start Visit')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn () => auth()->user()?->can('visits.create') ?? true)
                    ->slideOver()
                    ->modalWidth('7xl')
                    ->form([
                        Forms\Components\Section::make('Visit')
                            ->schema([
                                Forms\Components\Select::make('visit_type')
                                    ->options([
                                        'consultation' => 'Consultation',
                                        'procedure' => 'Procedure',
                                        'control' => 'Control',
                                        'mixed' => 'Mixed',
                                    ])
                                    ->default('procedure')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('started_at')
                                    ->default(now())
                                    ->required(),
                                Forms\Components\TextInput::make('procedure_title')
                                    ->label('Procedure type')
                                    ->required()
                                    ->maxLength(150),
                                Forms\Components\TextInput::make('zones')
                                    ->required()
                                    ->maxLength(120),
                                Forms\Components\TextInput::make('product_name')
                                    ->required()
                                    ->maxLength(150),
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('unit')
                                    ->default('ml')
                                    ->required()
                                    ->maxLength(20),
                                Forms\Components\Textarea::make('comments')->columnSpanFull(),
                            ])
                            ->columns(2),
                        Forms\Components\Section::make('Photos')
                            ->schema([
                                Forms\Components\FileUpload::make('before_photos')
                                    ->multiple()
                                    ->image()
                                    ->disk('local')
                                    ->directory('clinic/photos/before')
                                    ->visibility('private'),
                                Forms\Components\FileUpload::make('after_photos')
                                    ->multiple()
                                    ->image()
                                    ->disk('local')
                                    ->directory('clinic/photos/after')
                                    ->visibility('private'),
                            ])
                            ->columns(2),
                    ])
                    ->action(function (Appointment $record, array $data): void {
                        DB::transaction(function () use ($record, $data): void {
                            $visit = Visit::query()->create([
                                'patient_id' => $record->patient_id,
                                'appointment_id' => $record->id,
                                'location_id' => $record->location_id,
                                'room_id' => $record->room_id,
                                'doctor_user_id' => $record->doctor_user_id,
                                'started_at' => $data['started_at'],
                                'visit_type' => $data['visit_type'],
                                'status' => 'open',
                                'clinical_note' => $data['comments'] ?? null,
                                'created_by' => auth()->id(),
                                'updated_by' => auth()->id(),
                            ]);

                            $course = TreatmentCourse::query()->create([
                                'patient_id' => $record->patient_id,
                                'title' => $data['procedure_title'],
                                'status' => 'active',
                                'opened_at' => $data['started_at'],
                                'created_by' => auth()->id(),
                            ]);

                            $session = TreatmentSession::query()->create([
                                'treatment_course_id' => $course->id,
                                'visit_id' => $visit->id,
                                'session_kind' => 'primary',
                                'performed_at' => $data['started_at'],
                                'agreed_price' => $record->agreed_price,
                                'currency_code' => $record->currency_code,
                                'comments' => $data['comments'] ?? $data['procedure_title'],
                                'performed_by' => $record->doctor_user_id,
                            ]);

                            TreatmentSessionProductUsage::query()->create([
                                'treatment_session_id' => $session->id,
                                'product_name' => $data['product_name'],
                                'amount' => $data['amount'],
                                'unit' => $data['unit'],
                                'zone' => $data['zones'],
                                'notes' => $data['comments'] ?? null,
                            ]);

                            foreach (($data['before_photos'] ?? []) as $path) {
                                static::createClinicalFile($session, $path, 'before_photo');
                            }

                            foreach (($data['after_photos'] ?? []) as $path) {
                                static::createClinicalFile($session, $path, 'after_photo');
                            }

                            static::applyStatusIfExists($record, 'completed');
                        });

                        Notification::make()
                            ->title('Visit started and procedure captured')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('starts_at');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAppointments::route('/'),
        ];
    }

    protected static function applyStatusIfExists(Appointment $appointment, string $slug): void
    {
        $statusId = AppointmentStatus::query()
            ->where('slug', $slug)
            ->where(function (Builder $query) use ($appointment): void {
                $query->whereNull('location_id')
                    ->orWhere('location_id', $appointment->location_id);
            })
            ->value('id');

        if ($statusId !== null) {
            $appointment->status_id = $statusId;
            $appointment->updated_by = auth()->id();
            $appointment->save();
        }
    }

    protected static function createClinicalFile(TreatmentSession $session, string $path, string $category): void
    {
        ClinicalFile::query()->create([
            'fileable_type' => TreatmentSession::class,
            'fileable_id' => $session->id,
            'category' => $category,
            'disk' => 'local',
            'path' => $path,
            'original_name' => basename($path),
            'size_bytes' => 0,
            'sha256' => hash('sha256', $path),
            'is_private' => true,
            'uploaded_by' => auth()->id(),
            'uploaded_at' => now(),
        ]);
    }
}
