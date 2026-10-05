<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class NewsPost extends Model
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
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(NewsPostTranslation::class);
    }

    public function translationFor(string $locale): ?NewsPostTranslation
    {
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

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable')->latest();
    }
}
