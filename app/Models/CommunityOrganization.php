<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CommunityOrganization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_id',
        'registration_number',
        'email',
        'phone',
        'website',
        'facebook_url',
        'address',
        'logo',
        'cover_image',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'featured',
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

    public function organizationType(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class, 'type_id');
    }

    public function type(): BelongsTo
    {
        return $this->organizationType();
    }

    public function getContactPhoneAttribute(): ?string
    {
        return $this->phone;
    }

    public function getContactEmailAttribute(): ?string
    {
        return $this->email;
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CommunityOrganizationTranslation::class, 'organization_id');
    }

    public function translationFor(string $locale): ?CommunityOrganizationTranslation
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
