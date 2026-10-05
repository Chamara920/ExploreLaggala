<?php

namespace App\Filament\Resources\Hospitals\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HospitalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hospital Information')
                    ->columns(2)
                    ->schema([
                        Select::make('type')
                            ->label('Hospital / Facility Type')
                            ->options([
                                'government' => 'Government Hospital (රජයේ රෝහල)',
                                'private' => 'Private Hospital (පෞද්ගලික රෝහල)',
                                'clinic' => 'Private Clinic / Dispensary (සායනය)',
                                'pharmacy' => 'Pharmacy / Chemist (ඖෂධසල)',
                                'ayurvedic' => 'Ayurvedic Medical Center (ආයුර්වේද)',
                            ])
                            ->default('government')
                            ->required(),

                        TextInput::make('category')
                            ->label('Classification / Level')
                            ->placeholder('e.g. Base Hospital, Divisional Hospital, PMCU')
                            ->maxLength(100),

                        TextInput::make('phone')
                            ->label('General Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        TextInput::make('emergency_phone')
                            ->label('Emergency / ETU Hotline')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('ambulance_phone')
                            ->label('Ambulance Direct Line')
                            ->tel()
                            ->maxLength(50),

                        Toggle::make('has_ambulance')
                            ->label('Ambulance Service Available (ගිලන්රථ සේවය ඇත)')
                            ->default(false),

                        Toggle::make('has_emergency_unit')
                            ->label('Emergency Unit / ETU (හදිසි ප්‍රතිකාර ඒකකය)')
                            ->default(true),

                        Toggle::make('is_24x7')
                            ->label('Open 24/7 (පැය 24 පුරා විවෘතයි)')
                            ->default(true),

                        TextInput::make('operating_hours')
                            ->label('Operating Hours (if not 24/7)')
                            ->placeholder('e.g. 8:00 AM - 8:00 PM')
                            ->maxLength(100),

                        TextInput::make('city')
                            ->label('City / Town')
                            ->placeholder('e.g. Laggala-Pallegama, Rattota, Matale, Naula')
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
                            ->label('Google Maps Link')
                            ->url()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Facility Photo')
                            ->directory('emergency/hospitals')
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
                    ->description('Provide hospital name, address, facilities in Sinhala, English, and Tamil')
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
                                    ->label('Hospital / Clinic Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('address')
                                    ->label('Address / Location Details')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('available_facilities')
                                    ->label('Available Facilities & Services')
                                    ->placeholder('e.g. OPD, Emergency Unit, Maternity, Laboratory, Pharmacy, Ward Admissions')
                                    ->rows(2)
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Description / Special Notes')
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
