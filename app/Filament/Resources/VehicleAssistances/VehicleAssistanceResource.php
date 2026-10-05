<?php

namespace App\Filament\Resources\VehicleAssistances;

use App\Filament\Resources\VehicleAssistances\Pages\CreateVehicleAssistance;
use App\Filament\Resources\VehicleAssistances\Pages\EditVehicleAssistance;
use App\Filament\Resources\VehicleAssistances\Pages\ListVehicleAssistances;
use App\Filament\Resources\VehicleAssistances\Pages\ViewVehicleAssistance;
use App\Filament\Resources\VehicleAssistances\Schemas\VehicleAssistanceForm;
use App\Filament\Resources\VehicleAssistances\Schemas\VehicleAssistanceInfolist;
use App\Filament\Resources\VehicleAssistances\Tables\VehicleAssistancesTable;
use App\Models\VehicleAssistance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleAssistanceResource extends Resource
{
    protected static ?string $model = VehicleAssistance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|\UnitEnum|null $navigationGroup = 'EMERGENCY';

    protected static ?string $navigationLabel = 'Vehicle Assistance';

    protected static ?int $navigationSort = 5;

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
        return VehicleAssistanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VehicleAssistanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleAssistancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleAssistances::route('/'),
            'create' => CreateVehicleAssistance::route('/create'),
            'view' => ViewVehicleAssistance::route('/{record}'),
            'edit' => EditVehicleAssistance::route('/{record}/edit'),
        ];
    }
}
