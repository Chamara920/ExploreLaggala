<?php

namespace App\Filament\Resources\WildlifeForestOffices;

use App\Filament\Resources\WildlifeForestOffices\Pages\CreateWildlifeForestOffice;
use App\Filament\Resources\WildlifeForestOffices\Pages\EditWildlifeForestOffice;
use App\Filament\Resources\WildlifeForestOffices\Pages\ListWildlifeForestOffices;
use App\Filament\Resources\WildlifeForestOffices\Pages\ViewWildlifeForestOffice;
use App\Filament\Resources\WildlifeForestOffices\Schemas\WildlifeForestOfficeForm;
use App\Filament\Resources\WildlifeForestOffices\Schemas\WildlifeForestOfficeInfolist;
use App\Filament\Resources\WildlifeForestOffices\Tables\WildlifeForestOfficesTable;
use App\Models\WildlifeForestOffice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WildlifeForestOfficeResource extends Resource
{
    protected static ?string $model = WildlifeForestOffice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'EMERGENCY';

    protected static ?string $navigationLabel = 'Wildlife & Forest';

    protected static ?int $navigationSort = 4;

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
        return WildlifeForestOfficeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WildlifeForestOfficeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WildlifeForestOfficesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWildlifeForestOffices::route('/'),
            'create' => CreateWildlifeForestOffice::route('/create'),
            'view' => ViewWildlifeForestOffice::route('/{record}'),
            'edit' => EditWildlifeForestOffice::route('/{record}/edit'),
        ];
    }
}
