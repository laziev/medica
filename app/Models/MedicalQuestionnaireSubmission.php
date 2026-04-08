<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalQuestionnaireSubmission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'answers_json' => 'array',
            'presented_schema_snapshot_json' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MedicalQuestionnaireTemplate::class, 'template_id');
    }

    public function templateVersion(): BelongsTo
    {
        return $this->belongsTo(MedicalQuestionnaireTemplateVersion::class, 'template_version_id');
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function reviewedByDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_doctor_id');
    }

    public function signedDocument(): BelongsTo
    {
        return $this->belongsTo(SignedDocument::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
