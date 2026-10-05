<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExploreItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'explore_item_id',
        'image_path',
        'caption',
        'sort_order',
        'is_cover',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function exploreItem(): BelongsTo
    {
        return $this->belongsTo(ExploreItem::class);
    }
}
