<?php

namespace App\Filament\Resources\ServicePlaces\Tables;

use App\Models\ServicePlace;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServicePlacesTable
{
    public static function configure(Table $table): Table
    {
        $sectionFilterOptions = [];
        foreach (ServicePlace::SECTIONS as $key => $sec) {
            $sectionFilterOptions[$key] = $sec['title_en'];
        }

        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('translation_name')
                    ->label('Name')
                    ->state(fn (ServicePlace $record) => $record->translationFor('si')?->name ?? $record->translationFor('en')?->name ?? 'Untitled')
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('translations', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->weight('bold'),

                TextColumn::make('section')
                    ->label('Section')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ServicePlace::SECTIONS[$state]['title_en'] ?? ucfirst($state))
                    ->colors([
                        'danger' => 'health',
                        'success' => 'shops-businesses',
                        'info' => 'banks-atms',
                        'warning' => 'fuel-ev',
                        'primary' => 'education',
                    ]),

                TextColumn::make('sub_category')
                    ->label('Type')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'published',
                        'warning' => 'draft',
                        'danger' => 'archived',
                    ]),

                IconColumn::make('is_24_hours')
                    ->label('24/7')
                    ->boolean()
                    ->alignCenter(),

                IconColumn::make('featured')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('section')
                    ->options($sectionFilterOptions),

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
            ]);
    }
}
