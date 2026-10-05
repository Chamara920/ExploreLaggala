<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'start_date',
        'end_date',
        'start_time',
        'location_name',
        'google_maps_url',
        'organizer_name',
        'organizer_contact',
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
        'start_date' => 'date',
        'end_date' => 'date',
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
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(EventTranslation::class);
    }

    public function translationFor(string $locale): ?EventTranslation
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

    public function getEventDateAttribute(): mixed
    {
        return $this->start_date;
    }

    public function getLocationAttribute(): ?string
    {
        return $this->location_name;
    }

    public function getOrganizerAttribute(): ?string
    {
        return $this->organizer_name;
    }

    public function getContactPhoneAttribute(): ?string
    {
        return $this->organizer_contact;
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
