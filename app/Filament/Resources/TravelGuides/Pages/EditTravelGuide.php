<?php

namespace App\Filament\Resources\TravelGuides\Pages;

use App\Filament\Resources\TravelGuides\TravelGuideResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTravelGuide extends EditRecord
{
    protected static string $resource = TravelGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
