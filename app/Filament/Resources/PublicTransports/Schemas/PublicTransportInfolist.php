<?php

namespace App\Filament\Resources\PublicTransports\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PublicTransportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('route_name'),
                TextEntry::make('route_number')
                    ->placeholder('-'),
                TextEntry::make('transport_type'),
                TextEntry::make('from_location'),
                TextEntry::make('to_location'),
                TextEntry::make('departure_time')
                    ->placeholder('-'),
                TextEntry::make('arrival_time')
                    ->placeholder('-'),
                TextEntry::make('schedule_times')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('fare')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('fare_note')
                    ->placeholder('-'),
                TextEntry::make('operator_name')
                    ->placeholder('-'),
                TextEntry::make('contact_number')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('submitted_by')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('community_submitted')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
