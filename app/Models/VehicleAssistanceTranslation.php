<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleAssistanceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_assistance_id',
        'locale',
        'provider_name',
        'contact_person',
        'covered_areas',
        'services_offered',
        'address',
        'description',
    ];

    public function vehicleAssistance(): BelongsTo
    {
        return $this->belongsTo(VehicleAssistance::class);
    }
}
