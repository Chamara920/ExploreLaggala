<?php

namespace App\Filament\Resources\WildlifeForestOffices\Pages;

use App\Filament\Resources\WildlifeForestOffices\WildlifeForestOfficeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWildlifeForestOffice extends ViewRecord
{
    protected static string $resource = WildlifeForestOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
