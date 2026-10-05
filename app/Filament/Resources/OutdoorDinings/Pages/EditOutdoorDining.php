<?php

namespace App\Filament\Resources\OutdoorDinings\Pages;

use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use App\Filament\Resources\OutdoorDinings\OutdoorDiningResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOutdoorDining extends EditRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = OutdoorDiningResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->extractTranslationData($data, 'outdoor-dining');
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
