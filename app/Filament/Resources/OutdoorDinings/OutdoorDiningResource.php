<?php

namespace App\Filament\Resources\OutdoorDinings;

use App\Filament\Resources\OutdoorDinings\Pages\CreateOutdoorDining;
use App\Filament\Resources\OutdoorDinings\Pages\EditOutdoorDining;
use App\Filament\Resources\OutdoorDinings\Pages\ListOutdoorDinings;
use App\Filament\Resources\OutdoorDinings\Pages\ViewOutdoorDining;
use App\Filament\Resources\OutdoorDinings\Schemas\OutdoorDiningForm;
use App\Filament\Resources\OutdoorDinings\Tables\OutdoorDiningsTable;
use App\Models\OutdoorDining;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OutdoorDiningResource extends Resource
{
    protected static ?string $model = OutdoorDining::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSun;

    protected static string|\UnitEnum|null $navigationGroup = 'STAY & EAT';

    protected static ?string $navigationLabel = 'Outdoor Dining & Catering';

    protected static ?string $modelLabel = 'Outdoor Dining / Service';

    protected static ?string $pluralModelLabel = 'Outdoor Dining & Catering';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return OutdoorDiningForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OutdoorDiningsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOutdoorDinings::route('/'),
            'create' => CreateOutdoorDining::route('/create'),
            'view' => ViewOutdoorDining::route('/{record}'),
            'edit' => EditOutdoorDining::route('/{record}/edit'),
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
