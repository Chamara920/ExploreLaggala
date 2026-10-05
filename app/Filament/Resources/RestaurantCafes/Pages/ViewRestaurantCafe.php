<?php

namespace App\Filament\Resources\RestaurantCafes\Pages;

use App\Filament\Resources\RestaurantCafes\RestaurantCafeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRestaurantCafe extends ViewRecord
{
    protected static string $resource = RestaurantCafeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
