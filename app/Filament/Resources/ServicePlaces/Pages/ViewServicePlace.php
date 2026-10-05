<?php

namespace App\Filament\Resources\ServicePlaces\Pages;

use App\Filament\Resources\ServicePlaces\ServicePlaceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServicePlace extends ViewRecord
{
    protected static string $resource = ServicePlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
