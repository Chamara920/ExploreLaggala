<?php

namespace App\Filament\Resources\PoliceStations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PoliceStationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translations.name')
                    ->label('Station Name')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('division')
                    ->label('Division')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Desk Phone')
                    ->searchable(),

                TextColumn::make('emergency_phone')
                    ->label('Hotline')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('oic_phone')
                    ->label('OIC Phone')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('city')
                    ->label('City')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
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
