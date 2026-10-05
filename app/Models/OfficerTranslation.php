<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficerTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'officer_id',
        'locale',
        'name',
        'designation',
        'responsibilities',
    ];

    public function officer(): BelongsTo
    {
        return $this->belongsTo(Officer::class, 'officer_id');
    }
}
