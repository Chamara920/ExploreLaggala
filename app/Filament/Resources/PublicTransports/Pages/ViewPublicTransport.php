<?php

namespace App\Filament\Resources\PublicTransports\Pages;

use App\Filament\Resources\PublicTransports\PublicTransportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPublicTransport extends ViewRecord
{
    protected static string $resource = PublicTransportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
