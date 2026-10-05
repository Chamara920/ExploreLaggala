<?php

namespace App\Filament\Resources\LocalFoods\Tables;

use App\Models\StayEatCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LocalFoodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn ($record) => $record->translationFor('si')?->title ?? $record->translationFor('en')?->title ?? '—')
                    ->description(fn ($record) => $record->translationFor('en')?->title)
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('translations', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('category.name')
                    ->label('Type')
                    ->badge()
                    ->placeholder('Uncategorized'),

                TextColumn::make('location')
                    ->label('Location / Village')
                    ->getStateUsing(fn ($record) => $record->translationFor()?->location_name ?? '—')
                    ->limit(25),

                TextColumn::make('price_range')
                    ->label('Price')
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('featured')
                    ->boolean()
                    ->label('Featured'),

                TextColumn::make('images_count')
                    ->counts('images')
                    ->label('Photos')
                    ->badge()
                    ->color('info'),

                TextColumn::make('reviews_count')
                    ->counts('reviews')
                    ->label('Reviews')
                    ->badge(),

                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->label('Order'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Food Category')
                    ->options(fn () => StayEatCategory::where('section', 'local-food')->pluck('name', 'id')),
                SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'draft' => 'Draft',
                        'archived' => 'Archived',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
