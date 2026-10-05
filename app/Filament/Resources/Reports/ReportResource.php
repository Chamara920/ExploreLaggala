<?php

namespace App\Filament\Resources\Reports;

use App\Models\Report;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Content Reports';

    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Reported By')
                    ->searchable(),

                TextColumn::make('reportable_type')
                    ->label('Content Type')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                TextColumn::make('reportable_id')
                    ->label('Content ID'),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'resolved',
                        'gray' => 'dismissed',
                    ]),

                TextColumn::make('created_at')
                    ->label('Reported At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'resolved' => 'Resolved',
                        'dismissed' => 'Dismissed',
                    ]),
            ])
            ->actions([
                ViewAction::make(),

                Action::make('resolve')
                    ->label('Resolve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'resolved',
                            'resolved_by' => auth()->id(),
                            'resolved_at' => now(),
                        ]);
                    }),

                Action::make('dismiss')
                    ->label('Dismiss')
                    ->icon('heroicon-o-x-circle')
                    ->color('gray')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'dismissed',
                            'resolved_by' => auth()->id(),
                            'resolved_at' => now(),
                        ]);
                    }),

                DeleteAction::make(),
            ]);
    }
}
