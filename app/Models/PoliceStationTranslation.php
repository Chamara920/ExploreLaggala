<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoliceStationTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'police_station_id',
        'locale',
        'name',
        'address',
        'jurisdiction',
        'description',
    ];

    public function policeStation(): BelongsTo
    {
        return $this->belongsTo(PoliceStation::class);
    }
}
