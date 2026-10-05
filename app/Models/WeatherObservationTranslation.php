<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherObservationTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'weather_observation_id',
        'locale',
        'location_name',
        'wind_condition',
        'description',
    ];

    public function weatherObservation(): BelongsTo
    {
        return $this->belongsTo(WeatherObservation::class);
    }
}
