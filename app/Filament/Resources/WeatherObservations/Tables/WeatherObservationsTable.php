<?php

namespace App\Filament\Resources\WeatherObservations\Tables;

use App\Notifications\WeatherObservationReviewed;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WeatherObservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Reported By')
                    ->searchable(),
                TextColumn::make('location_name')
                    ->label('Location')
                    ->searchable(),
                TextColumn::make('condition')
                    ->badge()
                    ->colors([
                        'warning' => fn ($state) => in_array($state, ['sunny', 'partly_cloudy']),
                        'info' => fn ($state) => in_array($state, ['cloudy', 'mist', 'fog']),
                        'primary' => fn ($state) => in_array($state, ['rain', 'heavy_rain']),
                    ]),
                TextColumn::make('temperature')
                    ->suffix(' °C')
                    ->sortable(),
                TextColumn::make('rainfall')
                    ->suffix(' mm')
                    ->sortable(),
                TextColumn::make('observed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])

            ->recordActions([
                ViewAction::make(),

                EditAction::make(),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Weather Observation')
                    ->modalDescription(
                        'This observation will become publicly visible after approval.'
                    )
                    ->modalSubmitActionLabel('Approve')
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'approved',
                        ]);

                        $record->loadMissing('user');

                        if ($record->user) {
                            $record->user->notify(
                                new WeatherObservationReviewed(
                                    $record,
                                    'approved'
                                )
                            );
                        }

                        Notification::make()
                            ->title('Weather observation approved')
                            ->body(
                                "The {$record->location_name} observation is now publicly visible."
                            )
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Weather Observation')
                    ->modalDescription(
                        'This observation will not be displayed publicly.'
                    )
                    ->modalSubmitActionLabel('Reject')
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'rejected',
                        ]);

                        $record->loadMissing('user');

                        if ($record->user) {
                            $record->user->notify(
                                new WeatherObservationReviewed(
                                    $record,
                                    'rejected'
                                )
                            );
                        }

                        Notification::make()
                            ->title('Weather observation rejected')
                            ->body(
                                "The {$record->location_name} observation has been rejected."
                            )
                            ->danger()
                            ->send();
                    }),
            ])

            ->toolbarActions([
                            BulkActionGroup::make([
                                DeleteBulkAction::make(),
                            ]),
                        ]);
    }
}
