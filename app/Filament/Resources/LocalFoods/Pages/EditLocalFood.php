<?php

namespace App\Filament\Resources\LocalFoods\Pages;

use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use App\Filament\Resources\LocalFoods\LocalFoodResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLocalFood extends EditRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = LocalFoodResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->extractTranslationData($data, 'local-food');
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
