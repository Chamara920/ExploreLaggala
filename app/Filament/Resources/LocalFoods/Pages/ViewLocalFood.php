<?php

namespace App\Filament\Resources\LocalFoods\Pages;

use App\Filament\Resources\LocalFoods\LocalFoodResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLocalFood extends ViewRecord
{
    protected static string $resource = LocalFoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
