<?php

namespace App\Filament\Resources\Institutions\Tables;

use App\Models\Institution;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InstitutionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('translation_name')
                    ->label('Institution Name')
                    ->state(fn (Institution $record) => $record->translationFor('si')?->name ?? $record->translationFor('en')?->name ?? 'Untitled')
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('translations', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Institution::TYPES[$state] ?? ucfirst(str_replace('_', ' ', $state)))
                    ->colors([
                        'primary' => 'divisional_secretariat',
                        'success' => 'local_authority',
                        'warning' => 'mahaweli_institution',
                        'info' => 'government_department',
                        'gray' => fn ($state) => ! in_array($state, ['divisional_secretariat', 'local_authority', 'mahaweli_institution', 'government_department']),
                    ]),

                TextColumn::make('units_count')
                    ->counts('units')
                    ->label('Units/Depts')
                    ->alignCenter(),

                TextColumn::make('services_count')
                    ->counts('services')
                    ->label('Services')
                    ->alignCenter(),

                TextColumn::make('officers_count')
                    ->counts('officers')
                    ->label('Officers')
                    ->alignCenter(),

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
                SelectFilter::make('type')
                    ->options(Institution::TYPES),

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
