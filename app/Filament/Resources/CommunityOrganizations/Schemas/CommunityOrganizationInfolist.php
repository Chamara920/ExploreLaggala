<?php

namespace App\Filament\Resources\CommunityOrganizations\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommunityOrganizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Organization Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Submitted By'),

                        TextEntry::make('organizationType.name')
                            ->label('Organization Type'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'published' => 'success',
                                'pending_review' => 'warning',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('registration_number')
                            ->label('Registration Number'),

                        TextEntry::make('phone')
                            ->label('Contact Phone'),

                        TextEntry::make('email')
                            ->label('Contact Email'),

                        TextEntry::make('address')
                            ->label('Address')
                            ->columnSpanFull(),

                        TextEntry::make('website')
                            ->label('Website')
                            ->url(),

                        TextEntry::make('facebook_url')
                            ->label('Facebook')
                            ->url(),

                        TextEntry::make('reviewer.name')
                            ->label('Reviewed By'),

                        TextEntry::make('reviewed_at')
                            ->label('Reviewed At')
                            ->dateTime(),

                        TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($record) => $record?->status === 'rejected')
                            ->columnSpanFull(),

                        ImageEntry::make('logo')
                            ->label('Logo')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
