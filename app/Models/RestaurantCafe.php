<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class RestaurantCafe extends StayEatItem
{
    protected $table = 'stay_eat_items';

    protected static function booted(): void
    {
        static::addGlobalScope('restaurants_cafes', function (Builder $builder) {
            $builder->where('section', 'restaurants-cafes');
        });

        static::creating(function ($model) {
            $model->section = 'restaurants-cafes';
        });
    }
}
