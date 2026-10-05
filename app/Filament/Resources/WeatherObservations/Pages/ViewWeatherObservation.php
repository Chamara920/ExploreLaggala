<?php

namespace App\Filament\Resources\WeatherObservations\Pages;

use App\Filament\Resources\WeatherObservations\WeatherObservationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWeatherObservation extends ViewRecord
{
    protected static string $resource = WeatherObservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
