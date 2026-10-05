<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Submitted By'),

                        TextEntry::make('category.name')
                            ->label('Category'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'published' => 'success',
                                'pending_review' => 'warning',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('event_date')
                            ->label('Event Date')
                            ->dateTime(),

                        TextEntry::make('event_end_date')
                            ->label('Event End Date')
                            ->dateTime(),

                        TextEntry::make('location')
                            ->label('Location'),

                        TextEntry::make('organizer')
                            ->label('Organizer'),

                        TextEntry::make('contact_phone')
                            ->label('Contact Phone'),

                        TextEntry::make('contact_email')
                            ->label('Contact Email'),

                        IconEntry::make('is_free')
                            ->label('Free Event')
                            ->boolean(),

                        TextEntry::make('ticket_price')
                            ->label('Ticket Price')
                            ->money('LKR')
                            ->visible(fn ($record) => ! $record?->is_free),

                        TextEntry::make('reviewer.name')
                            ->label('Reviewed By'),

                        TextEntry::make('reviewed_at')
                            ->label('Reviewed At')
                            ->dateTime(),

                        TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($record) => $record?->status === 'rejected')
                            ->columnSpanFull(),

                        ImageEntry::make('cover_image')
                            ->label('Cover Image')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
