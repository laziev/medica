<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalSignatureEvidence extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'device_info_json' => 'array',
            'captured_at' => 'datetime',
        ];
    }

    public function signedDocument(): BelongsTo
    {
        return $this->belongsTo(SignedDocument::class);
    }

    public function signatureFile(): BelongsTo
    {
        return $this->belongsTo(ClinicalFile::class, 'signature_file_id');
    }

    public function witnessUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'witness_user_id');
    }
}
