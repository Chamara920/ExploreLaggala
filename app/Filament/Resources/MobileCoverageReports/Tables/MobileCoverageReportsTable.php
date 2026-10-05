<?php

namespace App\Filament\Resources\MobileCoverageReports\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MobileCoverageReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reporter.name')
                    ->label('Reporter')
                    ->searchable(),
                TextColumn::make('location_name')
                    ->label('Location')
                    ->searchable(),
                TextColumn::make('network_operator')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->searchable(),
                TextColumn::make('coverage_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),
                TextColumn::make('signal_strength')
                    ->badge()
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['excellent', 'good']),
                        'warning' => 'fair',
                        'danger' => fn ($state) => in_array($state, ['poor', 'none']),
                    ]),
                TextColumn::make('reported_at')
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
                SelectFilter::make('network_operator')
                    ->options([
                        'dialog' => 'Dialog',
                        'mobitel' => 'Mobitel',
                        'hutch' => 'Hutch',
                        'airtel' => 'Airtel',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['status' => 'approved'])),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['status' => 'rejected'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
