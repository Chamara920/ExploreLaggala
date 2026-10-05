<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WildlifeForestOfficeTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'wildlife_forest_office_id',
        'locale',
        'name',
        'address',
        'duties_description',
        'description',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(WildlifeForestOffice::class, 'wildlife_forest_office_id');
    }
}
