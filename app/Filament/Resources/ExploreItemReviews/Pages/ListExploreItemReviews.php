<?php

namespace App\Filament\Resources\ExploreItemReviews\Pages;

use App\Filament\Resources\ExploreItemReviews\ExploreItemReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListExploreItemReviews extends ListRecords
{
    protected static string $resource = ExploreItemReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
