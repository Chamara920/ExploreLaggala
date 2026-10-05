<?php

namespace App\Filament\Resources\DestinationReviews\Pages;

use App\Filament\Resources\DestinationReviews\DestinationReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDestinationReview extends EditRecord
{
    protected static string $resource = DestinationReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
