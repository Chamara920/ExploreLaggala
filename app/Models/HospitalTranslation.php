<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HospitalTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'locale',
        'name',
        'address',
        'description',
        'available_facilities',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }
}
