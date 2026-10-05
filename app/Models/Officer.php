<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Officer extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'unit_id',
        'photo',
        'phone',
        'email',
        'extension',
        'working_hours',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(InstitutionUnit::class, 'unit_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(OfficerTranslation::class, 'officer_id');
    }

    public function translationFor(?string $locale = null): ?OfficerTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }
}
