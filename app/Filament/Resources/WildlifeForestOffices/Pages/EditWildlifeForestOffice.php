<?php

namespace App\Filament\Resources\WildlifeForestOffices\Pages;

use App\Filament\Resources\WildlifeForestOffices\WildlifeForestOfficeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWildlifeForestOffice extends EditRecord
{
    protected static string $resource = WildlifeForestOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
