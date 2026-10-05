<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherLocationTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'weather_location_id',
        'locale',
        'name',
        'description',
    ];

    public function weatherLocation(): BelongsTo
    {
        return $this->belongsTo(WeatherLocation::class);
    }
}
