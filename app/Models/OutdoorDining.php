<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class OutdoorDining extends StayEatItem
{
    protected $table = 'stay_eat_items';

    protected static function booted(): void
    {
        static::addGlobalScope('outdoor_dining', function (Builder $builder) {
            $builder->where('section', 'outdoor-dining');
        });

        static::creating(function ($model) {
            $model->section = 'outdoor-dining';
        });
    }
}
