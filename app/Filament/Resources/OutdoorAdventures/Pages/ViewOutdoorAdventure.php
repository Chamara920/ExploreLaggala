<?php

namespace App\Filament\Resources\OutdoorAdventures\Pages;

use App\Filament\Resources\OutdoorAdventures\OutdoorAdventureResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOutdoorAdventure extends ViewRecord
{
    protected static string $resource = OutdoorAdventureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
