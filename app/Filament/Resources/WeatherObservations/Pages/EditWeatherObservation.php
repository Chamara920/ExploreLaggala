<?php

namespace App\Filament\Resources\WeatherObservations\Pages;

use App\Filament\Resources\WeatherObservations\WeatherObservationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWeatherObservation extends EditRecord
{
    protected static string $resource = WeatherObservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
