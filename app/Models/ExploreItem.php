<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExploreItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'type',
        'status',
        'featured',
        'latitude',
        'longitude',
        'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'sort_order' => 'integer',
    ];

    public function getForeignKey(): string
    {
        return 'explore_item_id';
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ExploreItemTranslation::class, 'explore_item_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ExploreItemImage::class, 'explore_item_id')->orderBy('sort_order');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExploreCategory::class, 'category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ExploreItemReview::class, 'explore_item_id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ExploreItemReview::class, 'explore_item_id')->where('status', 'approved');
    }

    public function translationFor(?string $locale = null): ?ExploreItemTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }

    public function coverImage(): ?ExploreItemImage
    {
        return $this->images->firstWhere('is_cover', true)
            ?? $this->images->first();
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) ($this->approvedReviews()->avg('rating') ?? 0), 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }
}
