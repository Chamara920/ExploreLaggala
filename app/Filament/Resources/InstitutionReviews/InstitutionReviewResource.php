<?php

namespace App\Filament\Resources\InstitutionReviews;

use App\Filament\Resources\InstitutionReviews\Pages\EditInstitutionReview;
use App\Filament\Resources\InstitutionReviews\Pages\ListInstitutionReviews;
use App\Filament\Resources\InstitutionReviews\Schemas\InstitutionReviewForm;
use App\Filament\Resources\InstitutionReviews\Tables\InstitutionReviewsTable;
use App\Models\InstitutionReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstitutionReviewResource extends Resource
{
    protected static ?string $model = InstitutionReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|\UnitEnum|null $navigationGroup = 'SERVICES';

    protected static ?string $navigationLabel = 'Gov Reviews';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return InstitutionReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstitutionReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstitutionReviews::route('/'),
            'edit' => EditInstitutionReview::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Reviews are submitted by users via public web pages
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
