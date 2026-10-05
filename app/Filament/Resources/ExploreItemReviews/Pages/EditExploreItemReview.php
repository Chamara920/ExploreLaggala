<?php

namespace App\Filament\Resources\ExploreItemReviews\Pages;

use App\Filament\Resources\ExploreItemReviews\ExploreItemReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExploreItemReview extends EditRecord
{
    protected static string $resource = ExploreItemReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
