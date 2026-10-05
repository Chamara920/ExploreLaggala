<?php

namespace App\Filament\Resources\MobileCoverageReports\Pages;

use App\Filament\Resources\MobileCoverageReports\MobileCoverageReportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMobileCoverageReport extends EditRecord
{
    protected static string $resource = MobileCoverageReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
