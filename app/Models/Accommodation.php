<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Accommodation extends StayEatItem
{
    protected $table = 'stay_eat_items';

    protected static function booted(): void
    {
        static::addGlobalScope('accommodation', function (Builder $builder) {
            $builder->where('section', 'accommodation');
        });

        static::creating(function ($model) {
            $model->section = 'accommodation';
        });
    }
}
