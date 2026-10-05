<?php

namespace App\Filament\Resources\InstitutionReviews\Pages;

use App\Filament\Resources\InstitutionReviews\InstitutionReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListInstitutionReviews extends ListRecords
{
    protected static string $resource = InstitutionReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
