<?php

namespace App\Filament\Resources\RestaurantCafes;

use App\Filament\Resources\RestaurantCafes\Pages\CreateRestaurantCafe;
use App\Filament\Resources\RestaurantCafes\Pages\EditRestaurantCafe;
use App\Filament\Resources\RestaurantCafes\Pages\ListRestaurantCafes;
use App\Filament\Resources\RestaurantCafes\Pages\ViewRestaurantCafe;
use App\Filament\Resources\RestaurantCafes\Schemas\RestaurantCafeForm;
use App\Filament\Resources\RestaurantCafes\Tables\RestaurantCafesTable;
use App\Models\RestaurantCafe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RestaurantCafeResource extends Resource
{
    protected static ?string $model = RestaurantCafe::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCake;

    protected static string|\UnitEnum|null $navigationGroup = 'STAY & EAT';

    protected static ?string $navigationLabel = 'Restaurants & Cafés';

    protected static ?string $modelLabel = 'Restaurant & Café';

    protected static ?string $pluralModelLabel = 'Restaurants & Cafés';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return RestaurantCafeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RestaurantCafesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRestaurantCafes::route('/'),
            'create' => CreateRestaurantCafe::route('/create'),
            'view' => ViewRestaurantCafe::route('/{record}'),
            'edit' => EditRestaurantCafe::route('/{record}/edit'),
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
