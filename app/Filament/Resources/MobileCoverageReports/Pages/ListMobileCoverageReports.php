<?php

namespace App\Filament\Resources\MobileCoverageReports\Pages;

use App\Filament\Resources\MobileCoverageReports\MobileCoverageReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMobileCoverageReports extends ListRecords
{
    protected static string $resource = MobileCoverageReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
