<?php

namespace App\Filament\Resources\CommunityOrganizations\Pages;

use App\Filament\Resources\CommunityOrganizations\CommunityOrganizationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCommunityOrganizations extends ListRecords
{
    protected static string $resource = CommunityOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
