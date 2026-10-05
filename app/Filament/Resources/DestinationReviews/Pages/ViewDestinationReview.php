<?php

namespace App\Filament\Resources\DestinationReviews\Pages;

use App\Filament\Resources\DestinationReviews\DestinationReviewResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDestinationReview extends ViewRecord
{
    protected static string $resource = DestinationReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
