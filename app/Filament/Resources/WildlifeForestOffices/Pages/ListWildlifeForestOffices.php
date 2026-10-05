<?php

namespace App\Filament\Resources\WildlifeForestOffices\Pages;

use App\Filament\Resources\WildlifeForestOffices\WildlifeForestOfficeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWildlifeForestOffices extends ListRecords
{
    protected static string $resource = WildlifeForestOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
