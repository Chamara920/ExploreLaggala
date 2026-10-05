<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileCoverageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'location_name',
        'latitude',
        'longitude',
        'network_operator',
        'coverage_type',
        'signal_strength',
        'description',
        'reported_by',
        'status',
        'reported_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'reported_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $report) {
            if (empty($report->reported_by)) {
                $report->reported_by = auth()->id() ?? User::query()->first()?->id ?? 1;
            }
            if (empty($report->reported_at)) {
                $report->reported_at = now();
            }
            if (empty($report->status)) {
                $report->status = 'approved';
            }
            if (empty($report->location_name) && ! empty($report->destination_id)) {
                $dest = Destination::with('translations')->find($report->destination_id);
                $report->location_name = $dest?->translations?->first()?->location_name
                    ?? $dest?->translations?->first()?->name
                    ?? 'Laggala';
                if (empty($report->latitude) && ! empty($dest?->latitude)) {
                    $report->latitude = $dest->latitude;
                }
                if (empty($report->longitude) && ! empty($dest?->longitude)) {
                    $report->longitude = $dest->longitude;
                }
            }
            if (empty($report->latitude) && ! empty($report->destination_id)) {
                $dest = Destination::find($report->destination_id);
                if (! empty($dest?->latitude)) {
                    $report->latitude = $dest->latitude;
                }
                if (! empty($dest?->longitude)) {
                    $report->longitude = $dest->longitude;
                }
            }
            if (empty($report->latitude)) {
                $report->latitude = 7.5450;
            }
            if (empty($report->longitude)) {
                $report->longitude = 80.7850;
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Get the destination this report is linked to.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get the user who reported this coverage.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * All locale translations for this coverage report.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(MobileCoverageReportTranslation::class);
    }

    // -------------------------------------------------------------------------
    // Translation helpers
    // -------------------------------------------------------------------------

    /**
     * Return the best translation for the given locale, falling back gracefully.
     */
    public function translationFor(?string $locale = null): ?MobileCoverageReportTranslation
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
        return $this->translationFor()?->location_name ?? $this->location_name ?? 'Location';
    }

    /**
     * Translated description, falling back to base column.
     */
    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->translationFor()?->description ?? $this->description;
    }

    // -------------------------------------------------------------------------
    // Attribute helpers
    // -------------------------------------------------------------------------

    /**
     * Human-readable operator label.
     */
    public function getOperatorLabelAttribute(): string
    {
        return match ($this->network_operator) {
            'dialog' => 'Dialog',
            'mobitel' => 'Mobitel',
            'hutch' => 'Hutch',
            'airtel' => 'Airtel',
            'multiple' => 'Multiple Networks',
            default => 'Other',
        };
    }

    /**
     * Signal strength badge color.
     */
    public function getSignalColorAttribute(): string
    {
        return match ($this->signal_strength) {
            'excellent' => 'green',
            'good' => 'emerald',
            'fair' => 'yellow',
            'poor' => 'orange',
            'none' => 'red',
            default => 'gray',
        };
    }
}
