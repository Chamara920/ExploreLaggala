<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'emergency_phone',
        'phone',
        'ambulance_phone',
        'has_ambulance',
        'has_emergency_unit',
        'is_24x7',
        'operating_hours',
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
        'has_ambulance' => 'boolean',
        'has_emergency_unit' => 'boolean',
        'is_24x7' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'sort_order' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(HospitalTranslation::class);
    }

    public function translationFor(?string $locale = null): ?HospitalTranslation
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
        return $this->translationFor()?->name ?? 'Hospital';
    }
}
