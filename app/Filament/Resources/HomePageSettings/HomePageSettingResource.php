<?php

namespace App\Filament\Resources\HomePageSettings;

use App\Filament\Resources\HomePageSettings\Pages\EditHomePageSetting;
use App\Filament\Resources\HomePageSettings\Pages\ListHomePageSettings;
use App\Filament\Resources\HomePageSettings\Schemas\HomePageSettingForm;
use App\Filament\Resources\HomePageSettings\Tables\HomePageSettingsTable;
use App\Models\HomePageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HomePageSettingResource extends Resource
{
    protected static ?string $model = HomePageSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|\UnitEnum|null $navigationGroup = 'Home Page';

    protected static ?string $navigationLabel = 'Featured Sections & Content';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return (auth()->user()?->hasRole('super_admin') ?? false)
            && HomePageSetting::count() === 0;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return HomePageSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomePageSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomePageSettings::route('/'),
            'edit' => EditHomePageSetting::route('/{record}/edit'),
        ];
    }
}
