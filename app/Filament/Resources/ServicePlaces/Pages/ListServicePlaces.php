<?php

namespace App\Filament\Resources\ServicePlaces\Pages;

use App\Filament\Resources\ServicePlaces\ServicePlaceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServicePlaces extends ListRecords
{
    protected static string $resource = ServicePlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
