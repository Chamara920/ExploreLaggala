<?php

namespace App\Filament\Resources\CommunityOrganizations\Pages;

use App\Filament\Resources\CommunityOrganizations\CommunityOrganizationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCommunityOrganization extends ViewRecord
{
    protected static string $resource = CommunityOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
