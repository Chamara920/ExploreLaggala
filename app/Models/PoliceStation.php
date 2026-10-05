<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoliceStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'division',
        'emergency_phone',
        'phone',
        'oic_phone',
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
        return $this->hasMany(PoliceStationTranslation::class);
    }

    public function translationFor(?string $locale = null): ?PoliceStationTranslation
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
        return $this->translationFor()?->name ?? 'Police Station';
    }
}
