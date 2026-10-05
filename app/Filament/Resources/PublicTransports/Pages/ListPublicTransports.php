<?php

namespace App\Filament\Resources\PublicTransports\Pages;

use App\Filament\Resources\PublicTransports\PublicTransportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPublicTransports extends ListRecords
{
    protected static string $resource = PublicTransportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
