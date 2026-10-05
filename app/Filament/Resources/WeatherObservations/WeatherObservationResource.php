<?php

namespace App\Filament\Resources\WeatherObservations;

use App\Filament\Resources\WeatherObservations\Pages\CreateWeatherObservation;
use App\Filament\Resources\WeatherObservations\Pages\EditWeatherObservation;
use App\Filament\Resources\WeatherObservations\Pages\ListWeatherObservations;
use App\Filament\Resources\WeatherObservations\Pages\ViewWeatherObservation;
use App\Filament\Resources\WeatherObservations\Schemas\WeatherObservationForm;
use App\Filament\Resources\WeatherObservations\Schemas\WeatherObservationInfolist;
use App\Filament\Resources\WeatherObservations\Tables\WeatherObservationsTable;
use App\Models\WeatherObservation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WeatherObservationResource extends Resource
{
    protected static ?string $model = WeatherObservation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSun;

    protected static string|\UnitEnum|null $navigationGroup = 'PLAN TRIP';

    protected static ?string $navigationLabel = 'Weather & Safety';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'location_name';

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
        return WeatherObservationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WeatherObservationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WeatherObservationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWeatherObservations::route('/'),
            'create' => CreateWeatherObservation::route('/create'),
            'view' => ViewWeatherObservation::route('/{record}'),
            'edit' => EditWeatherObservation::route('/{record}/edit'),
        ];
    }
}
