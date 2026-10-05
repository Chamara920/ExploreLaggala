<?php

namespace App\Filament\Resources\StayEatReviews;

use App\Filament\Resources\StayEatReviews\Pages\EditStayEatReview;
use App\Filament\Resources\StayEatReviews\Pages\ListStayEatReviews;
use App\Filament\Resources\StayEatReviews\Schemas\StayEatReviewForm;
use App\Filament\Resources\StayEatReviews\Tables\StayEatReviewsTable;
use App\Models\StayEatItemReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StayEatReviewResource extends Resource
{
    protected static ?string $model = StayEatItemReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|\UnitEnum|null $navigationGroup = 'STAY & EAT';

    protected static ?string $navigationLabel = 'Stay & Eat Reviews';

    protected static ?string $modelLabel = 'Review';

    protected static ?string $pluralModelLabel = 'Reviews';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return StayEatReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StayEatReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStayEatReviews::route('/'),
            'edit' => EditStayEatReview::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
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
