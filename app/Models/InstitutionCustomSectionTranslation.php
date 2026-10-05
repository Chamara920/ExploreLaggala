<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionCustomSectionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_custom_section_id',
        'locale',
        'title',
        'content',
    ];

    public function customSection(): BelongsTo
    {
        return $this->belongsTo(InstitutionCustomSection::class, 'institution_custom_section_id');
    }
}
