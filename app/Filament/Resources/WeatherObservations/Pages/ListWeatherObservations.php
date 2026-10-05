<?php

namespace App\Filament\Resources\WeatherObservations\Pages;

use App\Filament\Resources\WeatherObservations\WeatherObservationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeatherObservations extends ListRecords
{
    protected static string $resource = WeatherObservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
