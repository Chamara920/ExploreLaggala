<?php

namespace App\Filament\Resources\ServicePlaceReviews\Pages;

use App\Filament\Resources\ServicePlaceReviews\ServicePlaceReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListServicePlaceReviews extends ListRecords
{
    protected static string $resource = ServicePlaceReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
