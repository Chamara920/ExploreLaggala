<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomePageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'featured_destination_ids',
        'featured_blog_post_ids',
        'featured_news_post_ids',
        'featured_event_ids',
        'destinations_count',
        'blog_posts_count',
        'news_posts_count',
        'events_count',
    ];

    protected $casts = [
        'featured_destination_ids' => 'array',
        'featured_blog_post_ids' => 'array',
        'featured_news_post_ids' => 'array',
        'featured_event_ids' => 'array',
        'destinations_count' => 'integer',
        'blog_posts_count' => 'integer',
        'news_posts_count' => 'integer',
        'events_count' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(HomePageSettingTranslation::class);
    }

    public function translationFor(string $locale): ?HomePageSettingTranslation
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }
}
