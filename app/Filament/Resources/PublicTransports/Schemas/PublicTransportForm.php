<?php

namespace App\Filament\Resources\PublicTransports\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PublicTransportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Route Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('route_name')
                            ->label('Route Name (English Fallback)')
                            ->required(),

                        TextInput::make('route_number')
                            ->label('Route Number'),

                        Select::make('transport_type')
                            ->label('Transport Type')
                            ->options([
                                'bus' => 'Bus',
                                'train' => 'Train',
                                'taxi' => 'Taxi',
                                'tuk_tuk' => 'Tuk Tuk',
                                'private_hire' => 'Private Hire',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->default('bus'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('active'),

                        TextInput::make('from_location')
                            ->label('From Location (English Fallback)')
                            ->required(),

                        TextInput::make('to_location')
                            ->label('To Location (English Fallback)')
                            ->required(),

                        TextInput::make('departure_time')
                            ->label('Departure Time'),

                        TextInput::make('arrival_time')
                            ->label('Arrival Time'),

                        TextInput::make('fare')
                            ->label('Fare (LKR)')
                            ->numeric(),

                        TextInput::make('fare_note')
                            ->label('Fare Note (English Fallback)'),

                        TextInput::make('operator_name')
                            ->label('Operator Name (English Fallback)'),

                        TextInput::make('contact_number')
                            ->label('Contact Number')
                            ->tel(),

                        Textarea::make('notes')
                            ->label('Notes (English Fallback)')
                            ->columnSpanFull(),

                        Toggle::make('community_submitted')
                            ->label('Community Submitted')
                            ->required(),
                    ]),

                Section::make('Translations (භාෂා ත්‍රිත්වය — සිංහල / English / தமிழ்)')
                    ->description('Add route name, locations, and notes in each language.')
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

                                TextInput::make('route_name')
                                    ->label('Route Name / මාර්ගයේ නම / வழித்தட பெயர்')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('from_location')
                                    ->label('From / සිට / இடமிருந்து')
                                    ->maxLength(255),

                                TextInput::make('to_location')
                                    ->label('To / දක்වා / வரை')
                                    ->maxLength(255),

                                TextInput::make('operator_name')
                                    ->label('Operator Name / ක්‍රියාකරු / ஆபரேட்டர்')
                                    ->maxLength(255),

                                TextInput::make('fare_note')
                                    ->label('Fare Note / ගාස්තු සටහන / கட்டண குறிப்பு')
                                    ->maxLength(255),

                                Textarea::make('notes')
                                    ->label('Notes / සටහන් / குறிப்புகள்')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල — '.($state['route_name'] ?? 'මාර්ගය'),
                                'en' => 'English — '.($state['route_name'] ?? 'Route'),
                                'ta' => 'தமிழ் — '.($state['route_name'] ?? 'வழித்தட'),
                                default => $state['route_name'] ?? 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
