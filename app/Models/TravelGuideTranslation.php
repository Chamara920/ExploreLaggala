<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelGuideTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_guide_id',
        'locale',
        'title',
        'summary',
        'content',
    ];

    public function travelGuide(): BelongsTo
    {
        return $this->belongsTo(TravelGuide::class);
    }
}
