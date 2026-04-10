<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentSession extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'agreed_price' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(TreatmentCourse::class, 'treatment_course_id');
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function parentSession(): BelongsTo
    {
        return $this->belongsTo(TreatmentSession::class, 'parent_session_id');
    }

    public function correctionSessions(): HasMany
    {
        return $this->hasMany(TreatmentSession::class, 'parent_session_id');
    }

    public function serviceCatalogItem(): BelongsTo
    {
        return $this->belongsTo(ServiceCatalogItem::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function productUsages(): HasMany
    {
        return $this->hasMany(TreatmentSessionProductUsage::class);
    }

    public function signedDocuments(): HasMany
    {
        return $this->hasMany(SignedDocument::class);
    }
}
