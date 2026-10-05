<?php

namespace App\Filament\Resources\DestinationReviews\Pages;

use App\Filament\Resources\DestinationReviews\DestinationReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDestinationReviews extends ListRecords
{
    protected static string $resource = DestinationReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
