<?php

namespace App\Filament\Resources\VehicleAssistances\Pages;

use App\Filament\Resources\VehicleAssistances\VehicleAssistanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleAssistance extends ViewRecord
{
    protected static string $resource = VehicleAssistanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
