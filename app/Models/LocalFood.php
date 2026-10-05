<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class LocalFood extends StayEatItem
{
    protected $table = 'stay_eat_items';

    protected static function booted(): void
    {
        static::addGlobalScope('local_food', function (Builder $builder) {
            $builder->where('section', 'local-food');
        });

        static::creating(function ($model) {
            $model->section = 'local-food';
        });
    }
}
