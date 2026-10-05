<?php

namespace App\Filament\Resources\CultureHeritages\Pages;

use App\Filament\Resources\CultureHeritages\CultureHeritageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCultureHeritage extends ViewRecord
{
    protected static string $resource = CultureHeritageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
