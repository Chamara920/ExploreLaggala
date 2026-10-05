<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePlace extends Model
{
    use HasFactory;

    public const SECTIONS = [
        'health' => [
            'key' => 'health',
            'title_en' => 'Health Services',
            'title_si' => 'සෞඛ්‍ය සේවාවන්',
            'title_ta' => 'சுகாதார சேவைகள்',
            'icon' => 'bi-hospital',
            'badge' => '🏥 Health & Emergency Care',
            'sub_categories' => [
                'hospital' => 'Base / Divisional Hospital',
                'clinic' => 'Primary Health Care Clinic',
                'pharmacy' => 'Pharmacy / Medical Hall',
                'ayurvedic' => 'Ayurvedic Hospital / Center',
            ],
        ],
        'shops-businesses' => [
            'key' => 'shops-businesses',
            'title_en' => 'Shops & Businesses',
            'title_si' => 'වෙළඳසැල් සහ ව්‍යාපාර',
            'title_ta' => 'கடைகள் மற்றும் வணிகங்கள்',
            'icon' => 'bi-shop',
            'badge' => '🛍️ Local Commerce & Retail',
            'sub_categories' => [
                'grocery' => 'Grocery / Supermarket',
                'hardware' => 'Hardware & Building Supplies',
                'textile' => 'Clothing & Textiles',
                'electronics' => 'Electronics & Mobile Services',
                'services' => 'Professional / Trade Services',
            ],
        ],
        'banks-atms' => [
            'key' => 'banks-atms',
            'title_en' => 'Banks & ATMs',
            'title_si' => 'බැංකු සහ ස්වයංක්‍රීය ටෙලර් යන්ත්‍ර (ATMs)',
            'title_ta' => 'வங்கிகள் மற்றும் ஏடிஎம்கள்',
            'icon' => 'bi-bank2',
            'badge' => '💳 Financial & Banking Services',
            'sub_categories' => [
                'bank_branch' => 'Bank Branch',
                'atm' => 'ATM Booth',
                'finance' => 'Leasing & Finance Company',
                'money_transfer' => 'Money Transfer / Remittance',
            ],
        ],
        'fuel-ev' => [
            'key' => 'fuel-ev',
            'title_en' => 'Fuel & EV Charging',
            'title_si' => 'ඉන්ධන සහ විදුලි ආරෝපණ (EV)',
            'title_ta' => 'எரிபொருள் மற்றும் ஈவி சார்ஜிங்',
            'icon' => 'bi-fuel-pump',
            'badge' => '⛽ Energy, Fuel & Charging',
            'sub_categories' => [
                'filling_station' => 'Petrol / Diesel Station (Ceypetco/LIOC)',
                'ev_charging' => 'EV Charging Station',
                'auto_repair' => 'Tyre / Puncture & Auto Service',
            ],
        ],
        'education' => [
            'key' => 'education',
            'title_en' => 'Education',
            'title_si' => 'අධ්‍යාපන ආයතන',
            'title_ta' => 'கல்வி நிறுவனங்கள்',
            'icon' => 'bi-mortarboard',
            'badge' => '🎓 Schools & Vocational Institutes',
            'sub_categories' => [
                'school' => 'National / Maha Vidyalaya',
                'primary_school' => 'Primary School',
                'vocational' => 'Vocational Training Center',
                'library' => 'Public Library / Study Center',
            ],
        ],
    ];

    protected $fillable = [
        'section',
        'sub_category',
        'status',
        'featured',
        'image_path',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'emergency_hotline',
        'is_24_hours',
        'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_24_hours' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'sort_order' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ServicePlaceTranslation::class, 'service_place_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ServicePlaceReview::class, 'service_place_id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ServicePlaceReview::class, 'service_place_id')->where('status', 'approved');
    }

    public function translationFor(?string $locale = null): ?ServicePlaceTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->firstWhere('locale', 'si')
            ?? $this->translations->first();
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getSectionMetaAttribute(): array
    {
        return self::SECTIONS[$this->section] ?? [
            'key' => $this->section,
            'title_en' => ucfirst(str_replace('-', ' ', $this->section)),
            'title_si' => ucfirst(str_replace('-', ' ', $this->section)),
            'title_ta' => ucfirst(str_replace('-', ' ', $this->section)),
            'icon' => 'bi-geo-alt',
            'badge' => '📍 Local Service',
            'sub_categories' => [],
        ];
    }
}
