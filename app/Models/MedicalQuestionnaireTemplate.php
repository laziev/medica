<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalQuestionnaireTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function versions(): HasMany
    {
        return $this->hasMany(MedicalQuestionnaireTemplateVersion::class, 'template_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(MedicalQuestionnaireSubmission::class, 'template_id');
    }
}
