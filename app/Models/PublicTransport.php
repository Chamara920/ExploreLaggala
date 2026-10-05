<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicTransport extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'route_number',
        'transport_type',
        'bus_category',
        'from_location',
        'to_location',
        'key_stops',
        'departure_time',
        'arrival_time',
        'frequency',
        'schedule_times',
        'fare',
        'fare_note',
        'operator_name',
        'contact_number',
        'notes',
        'status',
        'is_suspended',
        'suspension_reason',
        'suspended_until',
        'submitted_by',
        'community_submitted',
    ];

    protected function casts(): array
    {
        return [
            'schedule_times' => 'array',
            'fare' => 'decimal:2',
            'is_suspended' => 'boolean',
            'community_submitted' => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Get the user who submitted this transport record.
     */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * All locale translations for this route.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PublicTransportTranslation::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope to only active routes.
     */
    public function scopeActive($query): mixed
    {
        return $query->where('status', 'active');
    }

    // -------------------------------------------------------------------------
    // Translation helpers
    // -------------------------------------------------------------------------

    /**
     * Return the best translation for the given locale, falling back gracefully.
     */
    public function translationFor(?string $locale = null): ?PublicTransportTranslation
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
     * Translated route name, falling back to base column.
     */
    public function getTranslatedRouteNameAttribute(): string
    {
        return $this->translationFor()?->route_name ?? $this->route_name ?? 'Route';
    }

    /**
     * Translated from_location, falling back to base column.
     */
    public function getTranslatedFromLocationAttribute(): ?string
    {
        return $this->translationFor()?->from_location ?? $this->from_location;
    }

    /**
     * Translated to_location, falling back to base column.
     */
    public function getTranslatedToLocationAttribute(): ?string
    {
        return $this->translationFor()?->to_location ?? $this->to_location;
    }

    /**
     * Translated notes, falling back to base column.
     */
    public function getTranslatedNotesAttribute(): ?string
    {
        return $this->translationFor()?->notes ?? $this->notes;
    }

    /**
     * Translated suspension reason.
     */
    public function getTranslatedSuspensionReasonAttribute(): ?string
    {
        return $this->translationFor()?->suspension_reason ?? $this->suspension_reason;
    }

    /**
     * Translated key stops along the route.
     */
    public function getTranslatedKeyStopsAttribute(): ?string
    {
        return $this->translationFor()?->key_stops ?? $this->key_stops;
    }

    // -------------------------------------------------------------------------
    // Attribute helpers
    // -------------------------------------------------------------------------

    /**
     * Human-readable transport type label.
     */
    public function getTransportTypeLabelAttribute(): string
    {
        return match ($this->transport_type) {
            'bus' => __('public_transport.type_bus'),
            'train' => __('public_transport.type_train'),
            'taxi' => __('public_transport.type_taxi'),
            'tuk_tuk' => __('public_transport.type_tuk_tuk'),
            'private_hire' => __('public_transport.type_private_hire'),
            default => __('public_transport.type_other'),
        };
    }

    /**
     * Human-readable bus category label.
     */
    public function getBusCategoryLabelAttribute(): string
    {
        return match ($this->bus_category) {
            'sltb' => __('public_transport.cat_sltb'),
            'private' => __('public_transport.cat_private'),
            'village_shuttle' => __('public_transport.cat_village_shuttle'),
            default => __('public_transport.cat_general'),
        };
    }
}
