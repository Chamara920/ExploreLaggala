<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DestinationSeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'month',
        'rating',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'destination_id' => 'integer',
            'month' => 'integer',
        ];
    }

    /**
     * Get the destination that owns this seasonal information.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
