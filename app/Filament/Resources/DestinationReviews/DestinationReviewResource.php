<?php

namespace App\Filament\Resources\DestinationReviews;

use App\Filament\Resources\DestinationReviews\Pages\CreateDestinationReview;
use App\Filament\Resources\DestinationReviews\Pages\EditDestinationReview;
use App\Filament\Resources\DestinationReviews\Pages\ListDestinationReviews;
use App\Filament\Resources\DestinationReviews\Pages\ViewDestinationReview;
use App\Filament\Resources\DestinationReviews\Schemas\DestinationReviewForm;
use App\Filament\Resources\DestinationReviews\Schemas\DestinationReviewInfolist;
use App\Filament\Resources\DestinationReviews\Tables\DestinationReviewsTable;
use App\Models\DestinationReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DestinationReviewResource extends Resource
{
    protected static ?string $model = DestinationReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'EXPLORE';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DestinationReviewForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DestinationReviewInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DestinationReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDestinationReviews::route('/'),
            'create' => CreateDestinationReview::route('/create'),
            'view' => ViewDestinationReview::route('/{record}'),
            'edit' => EditDestinationReview::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasAnyRole([
            'admin',
            'super_admin',
        ]) ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasAnyRole([
            'admin',
            'super_admin',
        ]) ?? false;
    }
}
