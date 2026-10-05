<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'content',
        'cover_image',
        'status',
        'featured',
        'sort_order',
        'author_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Get the author of this travel guide.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * All locale translations for this guide.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TravelGuideTranslation::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope to only published guides.
     */
    public function scopePublished($query): mixed
    {
        return $query->where('status', 'published');
    }

    // -------------------------------------------------------------------------
    // Translation helpers
    // -------------------------------------------------------------------------

    /**
     * Return the best translation for the given locale, falling back gracefully.
     */
    public function translationFor(?string $locale = null): ?TravelGuideTranslation
    {
        $locale = $locale ?: session('locale', app()->getLocale() ?: 'en');

        $translation = $this->translations->firstWhere('locale', $locale);
        if ($translation) {
            return $translation;
        }

        // When English is requested, do not fall back to Sinhala/Tamil.
        // Returning null allows the base model columns (English) to be used.
        if ($locale === 'en') {
            return null;
        }

        if ($locale === 'ta') {
            return $this->translations->firstWhere('locale', 'en')
                ?? $this->translations->firstWhere('locale', 'si');
        }

        if ($locale === 'si') {
            return $this->translations->firstWhere('locale', 'en');
        }

        return null;
    }

    /**
     * Get the publicly accessible cover image URL with reliable fallbacks.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (file_exists(public_path('storage/'.$this->cover_image)) || file_exists(storage_path('app/public/'.$this->cover_image))) {
                return asset('storage/'.$this->cover_image);
            }
        }

        // Fallback: check matching Destination images
        $destination = Destination::where('slug', $this->slug)->first();
        if ($destination && $destination->images->isNotEmpty()) {
            $destImg = $destination->images->first()->image_path;
            if (file_exists(public_path('storage/'.$destImg)) || file_exists(storage_path('app/public/'.$destImg))) {
                return asset('storage/'.$destImg);
            }
        }

        // Clean scenic fallback for Knuckles/Laggala
        return 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80';
    }

    /**
     * Translated title, falling back to the base column.
     */
    public function getTranslatedTitleAttribute(): string
    {
        return $this->translationFor()?->title ?? $this->title ?? 'Travel Guide';
    }

    /**
     * Translated summary, falling back to the base column.
     */
    public function getTranslatedSummaryAttribute(): ?string
    {
        return $this->translationFor()?->summary ?? $this->summary;
    }

    /**
     * Translated content, falling back to the base column.
     */
    public function getTranslatedContentAttribute(): ?string
    {
        return $this->translationFor()?->content ?? $this->content;
    }

    // -------------------------------------------------------------------------
    // Attribute helpers
    // -------------------------------------------------------------------------

    /**
     * Human-readable category label (localized).
     */
    public function getCategoryLabelAttribute(): string
    {
        $key = 'travel_guide.cat_'.$this->category;
        $translated = __($key);
        if ($translated !== $key) {
            return $translated;
        }

        return match ($this->category) {
            'getting_here' => 'Getting Here',
            'accommodation' => 'Accommodation',
            'food_drink' => 'Food & Drink',
            'safety_tips' => 'Safety Tips',
            'cultural_etiquette' => 'Cultural Etiquette',
            'packing_list' => 'Packing List',
            'best_time_to_visit' => 'Best Time to Visit',
            'local_customs' => 'Local Customs',
            'transportation' => 'Transportation',
            'money_budget' => 'Money & Budget',
            'health_medical' => 'Health & Medical',
            default => 'General',
        };
    }
}
