<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExploreCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ExploreItem::class, 'category_id')
            ->orderBy('sort_order');
    }
}
