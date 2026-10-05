<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyContactTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_contact_id',
        'locale',
        'name',
        'department',
        'description',
        'address',
    ];

    public function emergencyContact(): BelongsTo
    {
        return $this->belongsTo(EmergencyContact::class);
    }
}
