<?php

namespace App\Filament\Resources\ServicePlaceReviews;

use App\Filament\Resources\ServicePlaceReviews\Pages\EditServicePlaceReview;
use App\Filament\Resources\ServicePlaceReviews\Pages\ListServicePlaceReviews;
use App\Filament\Resources\ServicePlaceReviews\Schemas\ServicePlaceReviewForm;
use App\Filament\Resources\ServicePlaceReviews\Tables\ServicePlaceReviewsTable;
use App\Models\ServicePlaceReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServicePlaceReviewResource extends Resource
{
    protected static ?string $model = ServicePlaceReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|\UnitEnum|null $navigationGroup = 'SERVICES';

    protected static ?string $navigationLabel = 'Service Reviews';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ServicePlaceReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicePlaceReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServicePlaceReviews::route('/'),
            'edit' => EditServicePlaceReview::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Submitted via public web pages by users
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
