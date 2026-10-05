<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicTransportTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_transport_id',
        'locale',
        'route_name',
        'from_location',
        'to_location',
        'key_stops',
        'fare_note',
        'operator_name',
        'notes',
        'suspension_reason',
    ];

    public function publicTransport(): BelongsTo
    {
        return $this->belongsTo(PublicTransport::class);
    }
}
