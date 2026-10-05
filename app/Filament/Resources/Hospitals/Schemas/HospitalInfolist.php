<?php

namespace App\Filament\Resources\Hospitals\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HospitalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hospital Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('type')->badge(),
                        TextEntry::make('category')->placeholder('-'),
                        TextEntry::make('phone'),
                        TextEntry::make('emergency_phone')->placeholder('-'),
                        TextEntry::make('ambulance_phone')->placeholder('-'),
                        IconEntry::make('has_ambulance')->boolean(),
                        IconEntry::make('has_emergency_unit')->boolean(),
                        IconEntry::make('is_24x7')->boolean(),
                        TextEntry::make('operating_hours')->placeholder('-'),
                        TextEntry::make('city')->placeholder('-'),
                        ImageEntry::make('image')->placeholder('-')->columnSpanFull(),
                    ]),

                Section::make('Translations')
                    ->schema([
                        RepeatableEntry::make('translations')
                            ->schema([
                                TextEntry::make('locale')->badge(),
                                TextEntry::make('name'),
                                TextEntry::make('address')->placeholder('-'),
                                TextEntry::make('available_facilities')->placeholder('-')->columnSpanFull(),
                                TextEntry::make('description')->placeholder('-')->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
