<?php

namespace App\Filament\Resources\PublicTransports;

use App\Filament\Resources\PublicTransports\Pages\CreatePublicTransport;
use App\Filament\Resources\PublicTransports\Pages\EditPublicTransport;
use App\Filament\Resources\PublicTransports\Pages\ListPublicTransports;
use App\Filament\Resources\PublicTransports\Pages\ViewPublicTransport;
use App\Filament\Resources\PublicTransports\Schemas\PublicTransportForm;
use App\Filament\Resources\PublicTransports\Schemas\PublicTransportInfolist;
use App\Filament\Resources\PublicTransports\Tables\PublicTransportsTable;
use App\Models\PublicTransport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PublicTransportResource extends Resource
{
    protected static ?string $model = PublicTransport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|\UnitEnum|null $navigationGroup = 'PLAN TRIP';

    protected static ?string $navigationLabel = 'Public Transport';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'route_name';

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
        return PublicTransportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PublicTransportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicTransportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicTransports::route('/'),
            'create' => CreatePublicTransport::route('/create'),
            'view' => ViewPublicTransport::route('/{record}'),
            'edit' => EditPublicTransport::route('/{record}/edit'),
        ];
    }
}
