<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'locale',
        'name',
        'slug',
        'location_name',
        'short_description',
        'description',
        'office_hours',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }
}
