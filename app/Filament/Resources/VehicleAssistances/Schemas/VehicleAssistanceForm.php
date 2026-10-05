<?php

namespace App\Filament\Resources\VehicleAssistances\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleAssistanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service & Contact Details')
                    ->columns(2)
                    ->schema([
                        Select::make('service_type')
                            ->label('Service Specialization')
                            ->options([
                                'towing' => 'Towing Service (වාහන ඇදගෙන යාම)',
                                'breakdown' => 'Roadside Breakdown Assistance (මඟ ඇනහිටීම් සහාය)',
                                'recovery_4x4' => '4x4 & Off-Road Winch Recovery (කඳුකර 4x4 මුදවාගැනීම)',
                                'tyre_repair' => 'Tyre Puncture & Vulcanizing (ටයර් අලුත්වැඩියාව)',
                                'mechanic' => 'Mobile Auto Mechanic (ජංගම කාර්මික සේවය)',
                                'multiple' => 'Complete Recovery & Garage (සියලු සේවා)',
                            ])
                            ->default('towing')
                            ->required(),

                        TextInput::make('city')
                            ->label('Base City / Town')
                            ->placeholder('e.g. Laggala-Pallegama, Rattota, Matale')
                            ->maxLength(100),

                        TextInput::make('primary_phone')
                            ->label('Primary Hotline (24/7 Mobile)')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        TextInput::make('secondary_phone')
                            ->label('Secondary / Alternative Phone')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('base_location')
                            ->label('Garage / Base Location')
                            ->placeholder('e.g. Pallegama Junction, Rattota Bazaar')
                            ->maxLength(150),

                        Toggle::make('is_24x7')
                            ->label('24/7 Service Available (පැය 24 පුරා ක්‍රියාත්මකයි)')
                            ->default(true),

                        Toggle::make('has_flatbed_tow')
                            ->label('Flatbed / Carrier Truck Available (පැතලි ඇදගෙන යාමේ රථ)')
                            ->default(false),

                        Toggle::make('has_4x4_recovery')
                            ->label('4x4 Winch Mountain Recovery Equipped (වින්ච් සහිත 4x4 රථ)')
                            ->default(false),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->placeholder('7.5500000'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->placeholder('80.7300000'),

                        TextInput::make('google_maps_url')
                            ->label('Google Maps Link')
                            ->url()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Vehicle / Garage Photo')
                            ->directory('emergency/vehicles')
                            ->image()
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'published' => 'Published',
                                'draft' => 'Draft',
                            ])
                            ->default('published')
                            ->required(),

                        TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Translations (භාෂා ත්‍රිත්වය - සිංහල / English / தமிழ்)')
                    ->description('Provider name, covered areas, and services in Sinhala, English, and Tamil')
                    ->schema([
                        Repeater::make('translations')
                            ->relationship('translations')
                            ->schema([
                                Select::make('locale')
                                    ->label('Language / භාෂාව')
                                    ->options([
                                        'si' => 'සිංහල (Sinhala)',
                                        'en' => 'English',
                                        'ta' => 'தமிழ் (Tamil)',
                                    ])
                                    ->required(),

                                TextInput::make('provider_name')
                                    ->label('Provider / Garage Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('contact_person')
                                    ->label('Contact Person / Mechanic Name')
                                    ->maxLength(150),

                                TextInput::make('covered_areas')
                                    ->label('Covered Mountain Passes & Routes')
                                    ->placeholder('e.g. Riverston Pass, Pitawala Dhowpatha, Rattota-Laggala Main Road, Illukkumbura')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('services_offered')
                                    ->label('Detailed Services Offered')
                                    ->placeholder('e.g. 24h Towing, Winch pullout, battery jump start, mobile tyre puncture, radiator coolant, fuel delivery')
                                    ->rows(2)
                                    ->columnSpanFull(),

                                TextInput::make('address')
                                    ->label('Garage Address')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Additional Details / Rates Information')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල — '.($state['provider_name'] ?? 'නම'),
                                'en' => 'English — '.($state['provider_name'] ?? 'Name'),
                                'ta' => 'தமிழ் — '.($state['provider_name'] ?? 'பெயர்'),
                                default => $state['provider_name'] ?? 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
