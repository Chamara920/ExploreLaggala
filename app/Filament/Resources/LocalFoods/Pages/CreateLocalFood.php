<?php

namespace App\Filament\Resources\LocalFoods\Pages;

use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use App\Filament\Resources\LocalFoods\LocalFoodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocalFood extends CreateRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = LocalFoodResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractTranslationData($data, 'local-food');
    }

    protected function afterCreate(): void
    {
        $this->saveTranslations();
    }
}
