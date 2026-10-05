<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DestinationPlannerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'visit_duration_minutes',
        'opening_time',
        'closing_time',
        'entry_fee',
        'difficulty',
        'planner_enabled',
        'notes',
        'nearest_bus_stop',
        'bus_routes',
        'transport_accessibility',
        'recommended_vehicle',
        'parking_available',
        'mobile_signal_level',
        'best_mobile_networks',
        'connectivity_notes',
    ];

    protected function casts(): array
    {
        return [
            'destination_id' => 'integer',
            'visit_duration_minutes' => 'integer',
            'entry_fee' => 'decimal:2',
            'planner_enabled' => 'boolean',
            'parking_available' => 'boolean',
        ];
    }

    /**
     * Get the destination that owns the planner details.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
