<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSlideTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_slide_id',
        'locale',
        'badge',
        'title',
        'subtitle',
        'primary_button_text',
        'secondary_button_text',
        'planner_button_text',
        'planner_button_subtitle',
    ];

    public function homeSlide(): BelongsTo
    {
        return $this->belongsTo(HomeSlide::class);
    }
}
