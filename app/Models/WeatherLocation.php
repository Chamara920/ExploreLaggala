<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeatherLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'latitude',
        'longitude',
        'elevation_m',
        'description',
        'is_active',
        'sort_order',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'elevation_m' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(WeatherLocationTranslation::class);
    }

    public function observations(): HasMany
    {
        return $this->hasMany(WeatherObservation::class);
    }

    public function safetyAlerts(): HasMany
    {
        return $this->hasMany(SafetyAlert::class);
    }

    public function translationFor(?string $locale = null): ?WeatherLocationTranslation
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
        return $this->translationFor()?->name ?? $this->name;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->translationFor()?->description ?? $this->description;
    }
}
