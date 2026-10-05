<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StayEatItemTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stay_eat_item_id',
        'locale',
        'title',
        'slug',
        'location_name',
        'short_description',
        'description',
        'opening_hours',
    ];

    public function stayEatItem(): BelongsTo
    {
        return $this->belongsTo(StayEatItem::class, 'stay_eat_item_id');
    }
}
