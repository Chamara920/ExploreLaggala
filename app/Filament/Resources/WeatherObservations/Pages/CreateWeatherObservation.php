<?php

namespace App\Filament\Resources\WeatherObservations\Pages;

use App\Filament\Resources\WeatherObservations\WeatherObservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWeatherObservation extends CreateRecord
{
    protected static string $resource = WeatherObservationResource::class;
}
