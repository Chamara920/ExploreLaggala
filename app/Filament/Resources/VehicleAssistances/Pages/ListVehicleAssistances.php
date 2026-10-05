<?php

namespace App\Filament\Resources\VehicleAssistances\Pages;

use App\Filament\Resources\VehicleAssistances\VehicleAssistanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleAssistances extends ListRecords
{
    protected static string $resource = VehicleAssistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
