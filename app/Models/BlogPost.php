<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'featured',
        'cover_image',
        'published_at',
        'views',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BlogPostTranslation::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function translationFor(string $locale): ?BlogPostTranslation
    {
        // First try requested language
        $translation = $this->translations
            ->firstWhere('locale', $locale);

        if ($translation) {
            return $translation;
        }

        // Tamil → English fallback
        if ($locale === 'ta') {
            return $this->translations
                ->firstWhere('locale', 'en');
        }

        // General fallback to English
        return $this->translations
            ->firstWhere('locale', 'en');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable')->latest();
    }
}
