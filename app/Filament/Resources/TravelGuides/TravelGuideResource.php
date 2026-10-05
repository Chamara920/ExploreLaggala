<?php

namespace App\Filament\Resources\TravelGuides;

use App\Filament\Resources\TravelGuides\Pages\CreateTravelGuide;
use App\Filament\Resources\TravelGuides\Pages\EditTravelGuide;
use App\Filament\Resources\TravelGuides\Pages\ListTravelGuides;
use App\Filament\Resources\TravelGuides\Pages\ViewTravelGuide;
use App\Filament\Resources\TravelGuides\Schemas\TravelGuideForm;
use App\Filament\Resources\TravelGuides\Schemas\TravelGuideInfolist;
use App\Filament\Resources\TravelGuides\Tables\TravelGuidesTable;
use App\Models\TravelGuide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TravelGuideResource extends Resource
{
    protected static ?string $model = TravelGuide::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'PLAN TRIP';

    protected static ?string $navigationLabel = 'Travel Guides';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

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
        return TravelGuideForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TravelGuideInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TravelGuidesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTravelGuides::route('/'),
            'create' => CreateTravelGuide::route('/create'),
            'view' => ViewTravelGuide::route('/{record}'),
            'edit' => EditTravelGuide::route('/{record}/edit'),
        ];
    }
}
