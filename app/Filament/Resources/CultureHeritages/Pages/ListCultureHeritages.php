<?php

namespace App\Filament\Resources\CultureHeritages\Pages;

use App\Filament\Resources\CultureHeritages\CultureHeritageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCultureHeritages extends ListRecords
{
    protected static string $resource = CultureHeritageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Culture & Heritage Item'),
        ];
    }
}
