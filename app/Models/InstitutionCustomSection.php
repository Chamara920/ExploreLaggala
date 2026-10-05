<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitutionCustomSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'type',
        'data',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const SECTION_TYPES = [
        'text' => 'Rich Text / Description',
        'services_list' => 'Key Services Overview',
        'officers_list' => 'Leadership & Officers Roster',
        'contact_info' => 'Directory & Emergency Contacts',
        'important_links' => 'Important Government Portals & Links',
        'documents' => 'Forms, Circulars & Documents Downloads',
        'notice_alert' => 'Public Notice / Alert Banner',
        'faq' => 'Frequently Asked Questions (FAQ)',
        'table' => 'Citizen Charter / Data Table',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(InstitutionCustomSectionTranslation::class, 'institution_custom_section_id');
    }

    public function translationFor(?string $locale = null): ?InstitutionCustomSectionTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }
}
