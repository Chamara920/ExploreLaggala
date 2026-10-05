<?php

namespace App\Filament\Resources\OutdoorAdventures;

use App\Filament\Resources\OutdoorAdventures\Pages\CreateOutdoorAdventure;
use App\Filament\Resources\OutdoorAdventures\Pages\EditOutdoorAdventure;
use App\Filament\Resources\OutdoorAdventures\Pages\ListOutdoorAdventures;
use App\Filament\Resources\OutdoorAdventures\Pages\ViewOutdoorAdventure;
use App\Filament\Resources\OutdoorAdventures\Schemas\OutdoorAdventureForm;
use App\Filament\Resources\OutdoorAdventures\Tables\OutdoorAdventuresTable;
use App\Models\OutdoorAdventure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OutdoorAdventureResource extends Resource
{
    protected static ?string $model = OutdoorAdventure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'EXPLORE';

    protected static ?string $navigationLabel = 'Outdoor & Adventure';

    protected static ?string $modelLabel = 'Outdoor & Adventure Item';

    protected static ?string $pluralModelLabel = 'Outdoor & Adventure Items';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return OutdoorAdventureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OutdoorAdventuresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOutdoorAdventures::route('/'),
            'create' => CreateOutdoorAdventure::route('/create'),
            'view' => ViewOutdoorAdventure::route('/{record}'),
            'edit' => EditOutdoorAdventure::route('/{record}/edit'),
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
