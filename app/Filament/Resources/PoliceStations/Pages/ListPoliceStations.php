<?php

namespace App\Filament\Resources\PoliceStations\Pages;

use App\Filament\Resources\PoliceStations\PoliceStationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPoliceStations extends ListRecords
{
    protected static string $resource = PoliceStationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
