<?php

namespace App\Filament\Resources\ServicePlaceReviews\Pages;

use App\Filament\Resources\ServicePlaceReviews\ServicePlaceReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServicePlaceReview extends EditRecord
{
    protected static string $resource = ServicePlaceReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
