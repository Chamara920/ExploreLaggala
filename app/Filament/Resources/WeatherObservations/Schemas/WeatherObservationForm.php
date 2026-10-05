<?php

namespace App\Filament\Resources\WeatherObservations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WeatherObservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Observation Details')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Reported By')
                            ->relationship('user', 'name')
                            ->required(),

                        TextInput::make('location_name')
                            ->label('Location Name (English Fallback)')
                            ->required(),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric(),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric(),

                        Select::make('condition')
                            ->label('Weather Condition')
                            ->options([
                                'sunny' => '☀️ Sunny',
                                'partly_cloudy' => '⛅ Partly Cloudy',
                                'cloudy' => '☁️ Cloudy',
                                'rain' => '🌧️ Rain',
                                'heavy_rain' => '⛈️ Heavy Rain',
                                'mist' => '🌫️ Mist',
                                'fog' => '🌁 Fog',
                                'windy' => '💨 Windy',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('temperature')
                            ->label('Temperature (°C)')
                            ->numeric(),

                        TextInput::make('rainfall')
                            ->label('Rainfall (mm)')
                            ->numeric(),

                        TextInput::make('wind_condition')
                            ->label('Wind Condition (English Fallback)'),

                        DateTimePicker::make('observed_at')
                            ->label('Observed At')
                            ->required(),

                        Textarea::make('description')
                            ->label('Description (English Fallback)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Translations (භාෂා ත්‍රිත්වය — සිංහල / English / தமிழ்)')
                    ->description('Add location name, wind condition, and description in each language.')
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

                                TextInput::make('location_name')
                                    ->label('Location / ස්ථානය / இடம்')
                                    ->maxLength(255),

                                TextInput::make('wind_condition')
                                    ->label('Wind Condition / සුළඟ / காற்று')
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->label('Description / විස්තරය / விளக்கம்')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල — '.($state['location_name'] ?? 'ස්ථානය'),
                                'en' => 'English — '.($state['location_name'] ?? 'Location'),
                                'ta' => 'தமிழ் — '.($state['location_name'] ?? 'இடம்'),
                                default => $state['location_name'] ?? 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
