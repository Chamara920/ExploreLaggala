<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitutionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'unit_id',
        'file_path',
        'file_type',
        'file_size',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(InstitutionUnit::class, 'unit_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(InstitutionDocumentTranslation::class, 'institution_document_id');
    }

    public function translationFor(?string $locale = null): ?InstitutionDocumentTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }
}
