<?php

namespace App\Filament\Resources\PoliceStations\Pages;

use App\Filament\Resources\PoliceStations\PoliceStationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPoliceStation extends ViewRecord
{
    protected static string $resource = PoliceStationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
