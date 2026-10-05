<?php

namespace App\Filament\Resources\RestaurantCafes\Pages;

use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use App\Filament\Resources\RestaurantCafes\RestaurantCafeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurantCafe extends CreateRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = RestaurantCafeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractTranslationData($data, 'restaurants-cafes');
    }

    protected function afterCreate(): void
    {
        $this->saveTranslations();
    }
}
