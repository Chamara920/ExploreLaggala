<?php

namespace App\Filament\Resources\PoliceStations\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PoliceStationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Police Station Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('division'),
                        TextEntry::make('city')->placeholder('-'),
                        TextEntry::make('phone')->label('Desk Phone'),
                        TextEntry::make('emergency_phone')->placeholder('-'),
                        TextEntry::make('oic_phone')->label('OIC Direct Phone')->placeholder('-'),
                        TextEntry::make('email')->placeholder('-'),
                        ImageEntry::make('image')->placeholder('-')->columnSpanFull(),
                    ]),

                Section::make('Translations')
                    ->schema([
                        RepeatableEntry::make('translations')
                            ->schema([
                                TextEntry::make('locale')->badge(),
                                TextEntry::make('name'),
                                TextEntry::make('address')->placeholder('-'),
                                TextEntry::make('jurisdiction')->label('Coverage Area')->placeholder('-')->columnSpanFull(),
                                TextEntry::make('description')->placeholder('-')->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
