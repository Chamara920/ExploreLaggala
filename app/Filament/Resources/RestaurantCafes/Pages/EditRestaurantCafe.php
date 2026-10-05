<?php

namespace App\Filament\Resources\RestaurantCafes\Pages;

use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use App\Filament\Resources\RestaurantCafes\RestaurantCafeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantCafe extends EditRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = RestaurantCafeResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->extractTranslationData($data, 'restaurants-cafes');
    }

    protected function afterSave(): void
    {
        $this->saveTranslations();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
