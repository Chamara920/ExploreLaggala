<?php

namespace App\Filament\Resources\DestinationReviews\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DestinationReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('destination')
                    ->label('Destination')
                    ->getStateUsing(function ($record) {
                        return $record->destination
                            ?->translationFor('en')
                            ?->name ?? '—';
                    })
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(function ($state) {
                        return str_repeat('★', (int) $state)
                            .str_repeat('☆', 5 - (int) $state);
                    }),

                TextColumn::make('comment')
                    ->label('Review')
                    ->limit(60)
                    ->wrap(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])

            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
