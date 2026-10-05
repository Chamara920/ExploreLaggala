<?php

namespace App\Filament\Resources\VehicleAssistances\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleAssistanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Provider Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('service_type')->badge(),
                        TextEntry::make('city')->placeholder('-'),
                        TextEntry::make('primary_phone')->label('Primary Phone'),
                        TextEntry::make('secondary_phone')->placeholder('-'),
                        IconEntry::make('is_24x7')->label('24/7')->boolean(),
                        IconEntry::make('has_flatbed_tow')->label('Flatbed Truck')->boolean(),
                        IconEntry::make('has_4x4_recovery')->label('4x4 Winch')->boolean(),
                        ImageEntry::make('image')->placeholder('-')->columnSpanFull(),
                    ]),

                Section::make('Translations')
                    ->schema([
                        RepeatableEntry::make('translations')
                            ->schema([
                                TextEntry::make('locale')->badge(),
                                TextEntry::make('provider_name'),
                                TextEntry::make('contact_person')->placeholder('-'),
                                TextEntry::make('covered_areas')->label('Covered Areas')->placeholder('-')->columnSpanFull(),
                                TextEntry::make('services_offered')->label('Services')->placeholder('-')->columnSpanFull(),
                                TextEntry::make('address')->placeholder('-')->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
