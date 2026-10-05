<?php

namespace App\Filament\Resources\InstitutionReviews\Pages;

use App\Filament\Resources\InstitutionReviews\InstitutionReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstitutionReview extends EditRecord
{
    protected static string $resource = InstitutionReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
