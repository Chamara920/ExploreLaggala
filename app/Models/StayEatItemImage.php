<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StayEatItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'stay_eat_item_id',
        'image_path',
        'caption',
        'sort_order',
        'is_cover',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function stayEatItem(): BelongsTo
    {
        return $this->belongsTo(StayEatItem::class, 'stay_eat_item_id');
    }
}
