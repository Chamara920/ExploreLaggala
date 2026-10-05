<?php

namespace App\Filament\Resources\Interests\Pages;

use App\Filament\Resources\Interests\InterestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInterest extends ViewRecord
{
    protected static string $resource = InterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
