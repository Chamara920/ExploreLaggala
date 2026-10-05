<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'featured',
        'latitude',
        'longitude',
        'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function translations(): HasMany
    {
        return $this->hasMany(DestinationTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(DestinationImage::class)
            ->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(DestinationReview::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Translation Helper
    |--------------------------------------------------------------------------
    */

    public function translationFor(string $locale): ?DestinationTranslation
    {
        return $this->translations
            ->firstWhere('locale', $locale)
            ?? $this->translations
                ->firstWhere('locale', 'en');
    }

    /*
    |--------------------------------------------------------------------------
    | Cover Image
    |--------------------------------------------------------------------------
    */

    /**
     * Get the planner details for the destination.
     */
    public function plannerDetails(): HasOne
    {
        return $this->hasOne(DestinationPlannerDetail::class);
    }

    public function coverImage(): ?DestinationImage
    {
        return $this->images
            ->firstWhere('is_cover', true)
            ?? $this->images->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Approved Reviews
    |--------------------------------------------------------------------------
    */

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(DestinationReview::class)
            ->where('status', 'approved');
    }

    /*
    |--------------------------------------------------------------------------
    | Average Rating
    |--------------------------------------------------------------------------
    */

    public function getAverageRatingAttribute(): float
    {
        return round(
            $this->approvedReviews()->avg('rating') ?? 0,
            1
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Review Count
    |--------------------------------------------------------------------------
    */

    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get the interests associated with the destination.
     */
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(
            Interest::class,
            'destination_interest'
        )->withTimestamps();
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(DestinationSeason::class);
    }

    public function mobileCoverageReports(): HasMany
    {
        return $this->hasMany(MobileCoverageReport::class);
    }

    public function approvedMobileCoverageReports(): HasMany
    {
        return $this->hasMany(MobileCoverageReport::class)->where('status', 'approved');
    }
}
