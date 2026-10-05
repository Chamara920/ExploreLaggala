<?php

namespace App\Filament\Resources\StayEatReviews\Pages;

use App\Filament\Resources\StayEatReviews\StayEatReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStayEatReview extends EditRecord
{
    protected static string $resource = StayEatReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
