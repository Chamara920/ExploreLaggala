<?php

namespace App\Filament\Resources\CommunityOrganizations\Pages;

use App\Filament\Resources\CommunityOrganizations\CommunityOrganizationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCommunityOrganization extends CreateRecord
{
    protected static string $resource = CommunityOrganizationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
