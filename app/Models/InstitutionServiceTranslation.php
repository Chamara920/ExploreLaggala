<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionServiceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_service_id',
        'locale',
        'title',
        'description',
        'requirements',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(InstitutionService::class, 'institution_service_id');
    }
}
