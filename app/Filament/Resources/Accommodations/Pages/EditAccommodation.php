<?php

namespace App\Filament\Resources\Accommodations\Pages;

use App\Filament\Resources\Accommodations\AccommodationResource;
use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAccommodation extends EditRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = AccommodationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->extractTranslationData($data, 'accommodation');
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
