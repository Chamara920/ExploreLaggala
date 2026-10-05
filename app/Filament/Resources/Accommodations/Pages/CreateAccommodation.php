<?php

namespace App\Filament\Resources\Accommodations\Pages;

use App\Filament\Resources\Accommodations\AccommodationResource;
use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreateAccommodation extends CreateRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = AccommodationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractTranslationData($data, 'accommodation');
    }

    protected function afterCreate(): void
    {
        $this->saveTranslations();
    }
}
