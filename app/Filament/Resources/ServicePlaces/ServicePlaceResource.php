<?php

namespace App\Filament\Resources\ServicePlaces;

use App\Filament\Resources\ServicePlaces\Pages\CreateServicePlace;
use App\Filament\Resources\ServicePlaces\Pages\EditServicePlace;
use App\Filament\Resources\ServicePlaces\Pages\ListServicePlaces;
use App\Filament\Resources\ServicePlaces\Pages\ViewServicePlace;
use App\Filament\Resources\ServicePlaces\Schemas\ServicePlaceForm;
use App\Filament\Resources\ServicePlaces\Tables\ServicePlacesTable;
use App\Models\ServicePlace;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServicePlaceResource extends Resource
{
    protected static ?string $model = ServicePlace::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|\UnitEnum|null $navigationGroup = 'SERVICES';

    protected static ?string $navigationLabel = 'Service Places';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ServicePlaceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicePlacesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServicePlaces::route('/'),
            'create' => CreateServicePlace::route('/create'),
            'view' => ViewServicePlace::route('/{record}'),
            'edit' => EditServicePlace::route('/{record}/edit'),
        ];
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
}
