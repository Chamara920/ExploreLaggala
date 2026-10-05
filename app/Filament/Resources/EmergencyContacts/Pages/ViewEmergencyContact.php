<?php

namespace App\Filament\Resources\EmergencyContacts\Pages;

use App\Filament\Resources\EmergencyContacts\EmergencyContactResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEmergencyContact extends ViewRecord
{
    protected static string $resource = EmergencyContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
