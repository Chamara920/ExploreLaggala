<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WildlifeForestOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_type',
        'range_area',
        'emergency_hotline',
        'phone',
        'officer_in_charge_phone',
        'alternate_phone',
        'email',
        'city',
        'latitude',
        'longitude',
        'google_maps_url',
        'image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'sort_order' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(WildlifeForestOfficeTranslation::class);
    }

    public function translationFor(?string $locale = null): ?WildlifeForestOfficeTranslation
    {
        $locale = $locale ?: session('locale', app()->getLocale() ?: 'si');

        $translation = $this->translations->firstWhere('locale', $locale);
        if ($translation) {
            return $translation;
        }

        if ($locale === 'ta') {
            return $this->translations->firstWhere('locale', 'en')
                ?? $this->translations->firstWhere('locale', 'si');
        }

        return $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->firstWhere('locale', 'si')
            ?? $this->translations->first();
    }

    public function getTranslatedNameAttribute(): string
    {
        return $this->translationFor()?->name ?? 'Wildlife & Forest Office';
    }
}
