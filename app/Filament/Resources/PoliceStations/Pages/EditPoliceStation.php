<?php

namespace App\Filament\Resources\PoliceStations\Pages;

use App\Filament\Resources\PoliceStations\PoliceStationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPoliceStation extends EditRecord
{
    protected static string $resource = PoliceStationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
