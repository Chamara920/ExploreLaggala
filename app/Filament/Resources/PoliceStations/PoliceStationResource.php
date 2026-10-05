<?php

namespace App\Filament\Resources\PoliceStations;

use App\Filament\Resources\PoliceStations\Pages\CreatePoliceStation;
use App\Filament\Resources\PoliceStations\Pages\EditPoliceStation;
use App\Filament\Resources\PoliceStations\Pages\ListPoliceStations;
use App\Filament\Resources\PoliceStations\Pages\ViewPoliceStation;
use App\Filament\Resources\PoliceStations\Schemas\PoliceStationForm;
use App\Filament\Resources\PoliceStations\Schemas\PoliceStationInfolist;
use App\Filament\Resources\PoliceStations\Tables\PoliceStationsTable;
use App\Models\PoliceStation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PoliceStationResource extends Resource
{
    protected static ?string $model = PoliceStation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'EMERGENCY';

    protected static ?string $navigationLabel = 'Police Stations';

    protected static ?int $navigationSort = 3;

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
        return PoliceStationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PoliceStationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PoliceStationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPoliceStations::route('/'),
            'create' => CreatePoliceStation::route('/create'),
            'view' => ViewPoliceStation::route('/{record}'),
            'edit' => EditPoliceStation::route('/{record}/edit'),
        ];
    }
}
