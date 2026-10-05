<?php

namespace App\Filament\Resources\CommunityOrganizations\Pages;

use App\Filament\Resources\CommunityOrganizations\CommunityOrganizationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCommunityOrganization extends EditRecord
{
    protected static string $resource = CommunityOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
