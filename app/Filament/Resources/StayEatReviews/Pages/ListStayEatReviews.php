<?php

namespace App\Filament\Resources\StayEatReviews\Pages;

use App\Filament\Resources\StayEatReviews\StayEatReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListStayEatReviews extends ListRecords
{
    protected static string $resource = StayEatReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
