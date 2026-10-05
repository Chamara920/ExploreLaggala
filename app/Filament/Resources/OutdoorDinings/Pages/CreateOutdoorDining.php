<?php

namespace App\Filament\Resources\OutdoorDinings\Pages;

use App\Filament\Resources\Concerns\HandlesStayEatItemTranslations;
use App\Filament\Resources\OutdoorDinings\OutdoorDiningResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOutdoorDining extends CreateRecord
{
    use HandlesStayEatItemTranslations;

    protected static string $resource = OutdoorDiningResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractTranslationData($data, 'outdoor-dining');
    }

    protected function afterCreate(): void
    {
        $this->saveTranslations();
    }
}
