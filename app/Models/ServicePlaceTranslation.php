<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePlaceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_place_id',
        'locale',
        'name',
        'slug',
        'location_name',
        'operating_hours',
        'short_description',
        'description',
        'key_facilities',
    ];

    public function servicePlace(): BelongsTo
    {
        return $this->belongsTo(ServicePlace::class, 'service_place_id');
    }
}
