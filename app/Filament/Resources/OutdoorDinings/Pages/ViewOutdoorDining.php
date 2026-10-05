<?php

namespace App\Filament\Resources\OutdoorDinings\Pages;

use App\Filament\Resources\OutdoorDinings\OutdoorDiningResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOutdoorDining extends ViewRecord
{
    protected static string $resource = OutdoorDiningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
