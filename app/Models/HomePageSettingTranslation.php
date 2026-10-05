<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomePageSettingTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_page_setting_id',
        'locale',
        'destinations_title',
        'destinations_subtitle',
        'blog_title',
        'blog_subtitle',
        'news_title',
        'news_subtitle',
        'events_title',
        'events_subtitle',
        'weather_title',
        'weather_subtitle',
    ];

    public function homePageSetting(): BelongsTo
    {
        return $this->belongsTo(HomePageSetting::class);
    }
}
