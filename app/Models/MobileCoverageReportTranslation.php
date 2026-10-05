<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileCoverageReportTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_coverage_report_id',
        'locale',
        'location_name',
        'description',
    ];

    public function mobileCoverageReport(): BelongsTo
    {
        return $this->belongsTo(MobileCoverageReport::class);
    }
}
