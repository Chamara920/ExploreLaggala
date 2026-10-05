<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StayEatCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'name',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(StayEatItem::class, 'category_id')->orderBy('sort_order');
    }

    public function scopeForSection($query, string $section)
    {
        return $query->where('section', $section);
    }
}
