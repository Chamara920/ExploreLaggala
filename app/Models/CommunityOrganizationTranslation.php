<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityOrganizationTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'locale',
        'name',
        'slug',
        'summary',
        'description',
        'services_offered',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(CommunityOrganization::class, 'organization_id');
    }
}
