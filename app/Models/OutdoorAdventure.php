<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class OutdoorAdventure extends ExploreItem
{
    protected $table = 'explore_items';

    protected static function booted(): void
    {
        static::addGlobalScope('outdoor_adventure', function (Builder $builder) {
            $builder->where('type', 'outdoor-adventure');
        });

        static::creating(function ($model) {
            $model->type = 'outdoor-adventure';
        });
    }
}
