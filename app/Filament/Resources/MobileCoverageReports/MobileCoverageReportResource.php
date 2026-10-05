<?php

namespace App\Filament\Resources\MobileCoverageReports;

use App\Filament\Resources\MobileCoverageReports\Pages\CreateMobileCoverageReport;
use App\Filament\Resources\MobileCoverageReports\Pages\EditMobileCoverageReport;
use App\Filament\Resources\MobileCoverageReports\Pages\ListMobileCoverageReports;
use App\Filament\Resources\MobileCoverageReports\Pages\ViewMobileCoverageReport;
use App\Filament\Resources\MobileCoverageReports\Schemas\MobileCoverageReportForm;
use App\Filament\Resources\MobileCoverageReports\Schemas\MobileCoverageReportInfolist;
use App\Filament\Resources\MobileCoverageReports\Tables\MobileCoverageReportsTable;
use App\Models\MobileCoverageReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MobileCoverageReportResource extends Resource
{
    protected static ?string $model = MobileCoverageReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;

    protected static string|\UnitEnum|null $navigationGroup = 'PLAN TRIP';

    protected static ?string $navigationLabel = 'Mobile Coverage';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'location_name';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return MobileCoverageReportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MobileCoverageReportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MobileCoverageReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMobileCoverageReports::route('/'),
            'create' => CreateMobileCoverageReport::route('/create'),
            'view' => ViewMobileCoverageReport::route('/{record}'),
            'edit' => EditMobileCoverageReport::route('/{record}/edit'),
        ];
    }
}
