<?php

namespace App\Filament\Resources\VehicleAssistances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VehicleAssistancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translations.provider_name')
                    ->label('Provider / Garage')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('service_type')
                    ->label('Service')
                    ->badge()
                    ->colors([
                        'danger' => 'towing',
                        'warning' => 'breakdown',
                        'success' => 'recovery_4x4',
                        'info' => 'mechanic',
                        'gray' => 'tyre_repair',
                        'primary' => 'multiple',
                    ]),

                TextColumn::make('primary_phone')
                    ->label('Phone Number')
                    ->searchable(),

                TextColumn::make('city')
                    ->label('Base City')
                    ->searchable(),

                IconColumn::make('is_24x7')
                    ->label('24/7')
                    ->boolean(),

                IconColumn::make('has_4x4_recovery')
                    ->label('4x4 Winch')
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
                SelectFilter::make('service_type')
                    ->options([
                        'towing' => 'Towing Service',
                        'breakdown' => 'Roadside Breakdown',
                        'recovery_4x4' => '4x4 Mountain Recovery',
                        'mechanic' => 'Mobile Mechanic',
                        'tyre_repair' => 'Tyre Repair',
                        'multiple' => 'Complete Garage',
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
