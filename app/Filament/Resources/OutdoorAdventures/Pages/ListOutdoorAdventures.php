<?php

namespace App\Filament\Resources\OutdoorAdventures\Pages;

use App\Filament\Resources\OutdoorAdventures\OutdoorAdventureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOutdoorAdventures extends ListRecords
{
    protected static string $resource = OutdoorAdventureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Outdoor & Adventure Item'),
        ];
    }
}
