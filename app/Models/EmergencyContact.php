<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'phone_number',
        'alternate_phone',
        'short_code',
        'website',
        'is_toll_free',
        'is_24x7',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_toll_free' => 'boolean',
        'is_24x7' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(EmergencyContactTranslation::class);
    }

    public function translationFor(?string $locale = null): ?EmergencyContactTranslation
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

    public function getTranslatedNameAttribute(): string
    {
        return $this->translationFor()?->name ?? 'Emergency Contact';
    }
}
