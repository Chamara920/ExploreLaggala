<?php

namespace App\Filament\Resources\MobileCoverageReports\Pages;

use App\Filament\Resources\MobileCoverageReports\MobileCoverageReportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMobileCoverageReport extends ViewRecord
{
    protected static string $resource = MobileCoverageReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
