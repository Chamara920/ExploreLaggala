<?php

namespace App\Filament\Resources\PublicTransports\Pages;

use App\Filament\Resources\PublicTransports\PublicTransportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPublicTransport extends EditRecord
{
    protected static string $resource = PublicTransportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
