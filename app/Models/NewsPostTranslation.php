<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsPostTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_post_id',
        'locale',
        'title',
        'slug',
        'excerpt',
        'content',
    ];

    public function newsPost(): BelongsTo
    {
        return $this->belongsTo(NewsPost::class);
    }
}
