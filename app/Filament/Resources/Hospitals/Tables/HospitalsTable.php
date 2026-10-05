<?php

namespace App\Filament\Resources\Hospitals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HospitalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translations.name')
                    ->label('Hospital Name')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'success' => 'government',
                        'info' => 'private',
                        'warning' => 'clinic',
                        'primary' => 'pharmacy',
                        'gray' => 'ayurvedic',
                    ]),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('emergency_phone')
                    ->label('Emergency Phone')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('city')
                    ->label('City')
                    ->searchable(),

                IconColumn::make('has_ambulance')
                    ->label('Ambulance')
                    ->boolean(),

                IconColumn::make('is_24x7')
                    ->label('24/7')
                    ->boolean(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'government' => 'Government Hospital',
                        'private' => 'Private Hospital',
                        'clinic' => 'Private Clinic / Dispensary',
                        'pharmacy' => 'Pharmacy',
                        'ayurvedic' => 'Ayurvedic Medical Center',
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
