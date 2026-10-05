<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafetyAlertTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'safety_alert_id',
        'locale',
        'title',
        'location_name',
        'description',
        'safety_instructions',
    ];

    public function safetyAlert(): BelongsTo
    {
        return $this->belongsTo(SafetyAlert::class);
    }
}
