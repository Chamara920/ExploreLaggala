<?php

namespace App\Filament\Resources\ExploreItemReviews;

use App\Filament\Resources\ExploreItemReviews\Pages\EditExploreItemReview;
use App\Filament\Resources\ExploreItemReviews\Pages\ListExploreItemReviews;
use App\Filament\Resources\ExploreItemReviews\Schemas\ExploreItemReviewForm;
use App\Filament\Resources\ExploreItemReviews\Tables\ExploreItemReviewsTable;
use App\Models\ExploreItemReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExploreItemReviewResource extends Resource
{
    protected static ?string $model = ExploreItemReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|\UnitEnum|null $navigationGroup = 'EXPLORE';

    protected static ?string $navigationLabel = 'Explore Reviews';

    protected static ?string $modelLabel = 'Explore Review';

    protected static ?string $pluralModelLabel = 'Explore Reviews';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ExploreItemReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExploreItemReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExploreItemReviews::route('/'),
            'edit' => EditExploreItemReview::route('/{record}/edit'),
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
