<?php

namespace App\Filament\Resources\WildlifeForestOffices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WildlifeForestOfficesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translations.name')
                    ->label('Office Name')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('department_type')
                    ->label('Department')
                    ->badge()
                    ->colors([
                        'success' => 'conservation',
                        'warning' => 'wildlife',
                        'info' => 'forest',
                    ]),

                TextColumn::make('range_area')
                    ->label('Range Area')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('emergency_hotline')
                    ->label('Emergency Hotline')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('department_type')
                    ->options([
                        'conservation' => 'Knuckles Conservation Center',
                        'wildlife' => 'Wildlife Conservation Department (DWC)',
                        'forest' => 'Forest Department Range Office',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'draft' => 'Draft',
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
            ->defaultSort('sort_order');
    }
}
