<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DestinationTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'locale',
        'name',
        'slug',
        'short_description',
        'description',
        'location_name',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
