<?php

namespace App\Filament\Resources\EmergencyContacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmergencyContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translations.name')
                    ->label('Service / Name')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('short_code')
                    ->label('Quick Dial')
                    ->badge()
                    ->color('danger')
                    ->searchable(),

                TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->searchable(),

                TextColumn::make('category')
                    ->badge()
                    ->colors([
                        'danger' => 'hotline',
                        'success' => 'medical',
                        'primary' => 'police',
                        'warning' => 'disaster',
                        'info' => 'utility',
                        'gray' => 'local',
                    ]),

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

                TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'hotline' => 'Emergency Hotline',
                        'medical' => 'Medical / Ambulance',
                        'police' => 'Police & Security',
                        'disaster' => 'Disaster Management',
                        'utility' => 'Public Utilities',
                        'local' => 'Local Authorities',
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
