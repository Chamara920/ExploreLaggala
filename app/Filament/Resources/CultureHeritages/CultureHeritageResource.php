<?php

namespace App\Filament\Resources\CultureHeritages;

use App\Filament\Resources\CultureHeritages\Pages\CreateCultureHeritage;
use App\Filament\Resources\CultureHeritages\Pages\EditCultureHeritage;
use App\Filament\Resources\CultureHeritages\Pages\ListCultureHeritages;
use App\Filament\Resources\CultureHeritages\Pages\ViewCultureHeritage;
use App\Filament\Resources\CultureHeritages\Schemas\CultureHeritageForm;
use App\Filament\Resources\CultureHeritages\Tables\CultureHeritagesTable;
use App\Models\CultureHeritage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CultureHeritageResource extends Resource
{
    protected static ?string $model = CultureHeritage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'EXPLORE';

    protected static ?string $navigationLabel = 'Culture & Heritage';

    protected static ?string $modelLabel = 'Culture & Heritage Item';

    protected static ?string $pluralModelLabel = 'Culture & Heritage Items';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CultureHeritageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CultureHeritagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCultureHeritages::route('/'),
            'create' => CreateCultureHeritage::route('/create'),
            'view' => ViewCultureHeritage::route('/{record}'),
            'edit' => EditCultureHeritage::route('/{record}/edit'),
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
