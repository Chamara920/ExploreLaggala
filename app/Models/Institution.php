<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'featured',
        'image_path',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'fax',
        'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'sort_order' => 'integer',
    ];

    public const TYPES = [
        'divisional_secretariat' => 'Divisional Secretariat',
        'local_authority' => 'Local Authority / Pradeshiya Sabha',
        'government_department' => 'Government Department',
        'provincial_institution' => 'Provincial Institution',
        'mahaweli_institution' => 'Mahaweli Authority / Institution',
        'health_institution' => 'Health Institution',
        'education_institution' => 'Education Institution',
        'other' => 'Other Public Institution',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(InstitutionTranslation::class, 'institution_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(InstitutionUnit::class, 'institution_id')->orderBy('sort_order');
    }

    public function rootUnits(): HasMany
    {
        return $this->hasMany(InstitutionUnit::class, 'institution_id')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function services(): HasMany
    {
        return $this->hasMany(InstitutionService::class, 'institution_id')->orderBy('sort_order');
    }

    public function officers(): HasMany
    {
        return $this->hasMany(Officer::class, 'institution_id')->orderBy('sort_order');
    }

    public function customSections(): HasMany
    {
        return $this->hasMany(InstitutionCustomSection::class, 'institution_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(InstitutionDocument::class, 'institution_id')->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(InstitutionReview::class, 'institution_id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(InstitutionReview::class, 'institution_id')
            ->where('status', 'approved')
            ->latest();
    }

    public function translationFor(?string $locale = null): ?InstitutionTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) ($this->approvedReviews()->avg('rating') ?? 0), 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }
}
