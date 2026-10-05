<?php

namespace App\Filament\Resources\RestaurantCafes\Pages;

use App\Filament\Resources\RestaurantCafes\RestaurantCafeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRestaurantCafes extends ListRecords
{
    protected static string $resource = RestaurantCafeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Restaurant / Café'),
        ];
    }
}
