<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeatherObservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weather_location_id',
        'location_name',
        'latitude',
        'longitude',
        'condition',
        'temperature',
        'rainfall',
        'wind_condition',
        'description',
        'observed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'temperature' => 'decimal:2',
            'rainfall' => 'decimal:2',
            'observed_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Get the user who submitted this weather observation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the weather location this observation belongs to.
     */
    public function weatherLocation(): BelongsTo
    {
        return $this->belongsTo(WeatherLocation::class);
    }

    /**
     * All locale translations for this observation.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(WeatherObservationTranslation::class);
    }

    // -------------------------------------------------------------------------
    // Translation helpers
    // -------------------------------------------------------------------------

    /**
     * Return the best translation for the given locale, falling back gracefully.
     */
    public function translationFor(?string $locale = null): ?WeatherObservationTranslation
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

    /**
     * Translated location name, falling back to base column.
     */
    public function getTranslatedLocationNameAttribute(): string
    {
        return $this->translationFor()?->location_name ?? $this->location_name ?? 'Laggala';
    }

    /**
     * Translated description, falling back to base column.
     */
    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->translationFor()?->description ?? $this->description;
    }
}
