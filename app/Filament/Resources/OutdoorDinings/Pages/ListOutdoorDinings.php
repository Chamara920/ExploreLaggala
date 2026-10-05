<?php

namespace App\Filament\Resources\OutdoorDinings\Pages;

use App\Filament\Resources\OutdoorDinings\OutdoorDiningResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOutdoorDinings extends ListRecords
{
    protected static string $resource = OutdoorDiningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Outdoor Dining / Service'),
        ];
    }
}
