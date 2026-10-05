<?php

namespace App\Filament\Resources\PoliceStations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PoliceStationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Station Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('division')
                            ->label('Police Division')
                            ->default('Matale Division')
                            ->maxLength(100),

                        TextInput::make('city')
                            ->label('City / Location')
                            ->placeholder('e.g. Laggala, Rattota, Naula, Wilgamuwa')
                            ->maxLength(100),

                        TextInput::make('phone')
                            ->label('Station Desk Phone')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        TextInput::make('emergency_phone')
                            ->label('Emergency / Direct Hotline')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('oic_phone')
                            ->label('OIC (Officer In Charge) Direct Mobile')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('alternate_phone')
                            ->label('Alternate / Traffic Branch Phone')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('email')
                            ->label('Station Official Email')
                            ->email()
                            ->maxLength(100),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->placeholder('7.5500000'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->placeholder('80.7300000'),

                        TextInput::make('google_maps_url')
                            ->label('Google Maps Location Link')
                            ->url()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Station Photo')
                            ->directory('emergency/police')
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
                    ->description('Station name, jurisdiction, and address in Sinhala, English, and Tamil')
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

                                TextInput::make('name')
                                    ->label('Police Station Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('address')
                                    ->label('Postal Address')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('jurisdiction')
                                    ->label('Jurisdiction & Coverage Area')
                                    ->placeholder('e.g. Laggala Pallegama, Illukkumbura, Riverston Pass, Pitawala, Haththota Amuna')
                                    ->rows(2)
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Important Notes / Special Units')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල — '.($state['name'] ?? 'නම'),
                                'en' => 'English — '.($state['name'] ?? 'Name'),
                                'ta' => 'தமிழ் — '.($state['name'] ?? 'பெயர்'),
                                default => $state['name'] ?? 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
