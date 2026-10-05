<?php

namespace App\Filament\Resources\LocalFoods;

use App\Filament\Resources\LocalFoods\Pages\CreateLocalFood;
use App\Filament\Resources\LocalFoods\Pages\EditLocalFood;
use App\Filament\Resources\LocalFoods\Pages\ListLocalFoods;
use App\Filament\Resources\LocalFoods\Pages\ViewLocalFood;
use App\Filament\Resources\LocalFoods\Schemas\LocalFoodForm;
use App\Filament\Resources\LocalFoods\Tables\LocalFoodsTable;
use App\Models\LocalFood;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LocalFoodResource extends Resource
{
    protected static ?string $model = LocalFood::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'STAY & EAT';

    protected static ?string $navigationLabel = 'Local Food';

    protected static ?string $modelLabel = 'Local Food Item';

    protected static ?string $pluralModelLabel = 'Local Food Items';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return LocalFoodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocalFoodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocalFoods::route('/'),
            'create' => CreateLocalFood::route('/create'),
            'view' => ViewLocalFood::route('/{record}'),
            'edit' => EditLocalFood::route('/{record}/edit'),
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
