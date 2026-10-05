<?php

namespace App\Filament\Resources\PublicTransports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PublicTransportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('route_name')
                    ->searchable(),
                TextColumn::make('route_number')
                    ->searchable(),
                TextColumn::make('transport_type')
                    ->searchable(),
                TextColumn::make('from_location')
                    ->searchable(),
                TextColumn::make('to_location')
                    ->searchable(),
                TextColumn::make('departure_time')
                    ->searchable(),
                TextColumn::make('arrival_time')
                    ->searchable(),
                TextColumn::make('fare')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fare_note')
                    ->searchable(),
                TextColumn::make('operator_name')
                    ->searchable(),
                TextColumn::make('contact_number')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('submitted_by')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('community_submitted')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
