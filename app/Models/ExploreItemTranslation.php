<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExploreItemTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'explore_item_id',
        'locale',
        'title',
        'slug',
        'short_description',
        'description',
        'location_name',
    ];

    public function exploreItem(): BelongsTo
    {
        return $this->belongsTo(ExploreItem::class);
    }
}
