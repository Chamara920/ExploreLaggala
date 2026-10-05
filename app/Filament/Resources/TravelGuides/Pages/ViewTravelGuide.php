<?php

namespace App\Filament\Resources\TravelGuides\Pages;

use App\Filament\Resources\TravelGuides\TravelGuideResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTravelGuide extends ViewRecord
{
    protected static string $resource = TravelGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
