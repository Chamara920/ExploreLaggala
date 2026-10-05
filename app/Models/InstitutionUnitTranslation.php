<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionUnitTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_unit_id',
        'locale',
        'name',
        'description',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(InstitutionUnit::class, 'institution_unit_id');
    }
}
