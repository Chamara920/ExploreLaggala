<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CultureHeritage extends ExploreItem
{
    protected $table = 'explore_items';

    protected static function booted(): void
    {
        static::addGlobalScope('culture_heritage', function (Builder $builder) {
            $builder->where('type', 'culture-heritage');
        });

        static::creating(function ($model) {
            $model->type = 'culture-heritage';
        });
    }
}
