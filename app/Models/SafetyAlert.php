<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SafetyAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'hazard_type',
        'severity',
        'location_name',
        'weather_location_id',
        'description',
        'safety_instructions',
        'is_active',
        'user_id',
        'reported_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'reported_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function weatherLocation(): BelongsTo
    {
        return $this->belongsTo(WeatherLocation::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SafetyAlertTranslation::class);
    }

    public function translationFor(?string $locale = null): ?SafetyAlertTranslation
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

    public function getTranslatedTitleAttribute(): string
    {
        return $this->translationFor()?->title ?? $this->title;
    }

    public function getTranslatedLocationNameAttribute(): string
    {
        return $this->translationFor()?->location_name ?? $this->location_name;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->translationFor()?->description ?? $this->description;
    }

    public function getTranslatedSafetyInstructionsAttribute(): ?string
    {
        return $this->translationFor()?->safety_instructions ?? $this->safety_instructions;
    }

    public function getHazardTypeLabelAttribute(): string
    {
        return match ($this->hazard_type) {
            'landslide' => __('weather_safety.hazard_landslide'),
            'rockfall' => __('weather_safety.hazard_rockfall'),
            'flash_flood' => __('weather_safety.hazard_flash_flood'),
            'high_wind' => __('weather_safety.hazard_high_wind'),
            'dense_mist' => __('weather_safety.hazard_dense_mist'),
            'road_closure' => __('weather_safety.hazard_road_closure'),
            default => __('weather_safety.hazard_other'),
        };
    }

    public function getSeverityLabelAttribute(): string
    {
        return match ($this->severity) {
            'danger' => __('weather_safety.severity_danger'),
            'warning' => __('weather_safety.severity_warning'),
            'advisory' => __('weather_safety.severity_advisory'),
            default => __('weather_safety.severity_advisory'),
        };
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        return match ($this->severity) {
            'danger' => 'bg-rose-600 text-white border-rose-700',
            'warning' => 'bg-amber-500 text-white border-amber-600',
            'advisory' => 'bg-sky-600 text-white border-sky-700',
            default => 'bg-slate-600 text-white border-slate-700',
        };
    }
}
