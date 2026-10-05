<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleAssistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'primary_phone',
        'secondary_phone',
        'is_24x7',
        'has_flatbed_tow',
        'has_4x4_recovery',
        'city',
        'base_location',
        'latitude',
        'longitude',
        'google_maps_url',
        'image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_24x7' => 'boolean',
        'has_flatbed_tow' => 'boolean',
        'has_4x4_recovery' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'sort_order' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(VehicleAssistanceTranslation::class);
    }

    public function translationFor(?string $locale = null): ?VehicleAssistanceTranslation
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
        return $this->translationFor()?->provider_name ?? 'Vehicle Assistance';
    }
}
