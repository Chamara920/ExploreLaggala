<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionDocumentTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_document_id',
        'locale',
        'title',
        'description',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(InstitutionDocument::class, 'institution_document_id');
    }
}
