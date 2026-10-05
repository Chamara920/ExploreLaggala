<?php

namespace App\Filament\Resources\LocalFoods\Pages;

use App\Filament\Resources\LocalFoods\LocalFoodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLocalFoods extends ListRecords
{
    protected static string $resource = LocalFoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Local Food / Item'),
        ];
    }
}
