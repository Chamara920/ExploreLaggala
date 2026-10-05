<?php

namespace App\Filament\Resources\WildlifeForestOffices\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WildlifeForestOfficeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Office Information')
                    ->columns(2)
                    ->schema([
                        Select::make('department_type')
                            ->label('Department / Agency Type')
                            ->options([
                                'conservation' => 'Knuckles Conservation Center (නකල්ස් සංරක්ෂණ මධ්‍යස්ථානය)',
                                'wildlife' => 'Wildlife Conservation Department - DWC (වනජීවී දෙපාර්තමේන්තුව)',
                                'forest' => 'Forest Department Range Office (වන සංරක්ෂණ දෙපාර්තමේන්තුව)',
                            ])
                            ->default('conservation')
                            ->required(),

                        TextInput::make('range_area')
                            ->label('Range / Beat Area')
                            ->placeholder('e.g. Knuckles Range - Illukkumbura, Riverston, Pitawala')
                            ->maxLength(100),

                        TextInput::make('phone')
                            ->label('Office Contact Phone')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        TextInput::make('emergency_hotline')
                            ->label('Emergency Wildlife / Rescue Hotline')
                            ->placeholder('e.g. Wild Elephant Attacks, Forest Fires, Lost Hikers')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('officer_in_charge_phone')
                            ->label('Range Officer / OIC Mobile')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('alternate_phone')
                            ->label('Alternate Phone')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('email')
                            ->label('Official Email')
                            ->email()
                            ->maxLength(100),

                        TextInput::make('city')
                            ->label('City / Town')
                            ->placeholder('e.g. Illukkumbura, Laggala, Rattota')
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
                            ->label('Office Photo')
                            ->directory('emergency/wildlife')
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
                    ->description('Office title, permits description, and address in Sinhala, English, and Tamil')
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
                                    ->label('Office Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('address')
                                    ->label('Postal Address / Exact Location')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('duties_description')
                                    ->label('Permits, Trekking Guidelines & Rescue Duties')
                                    ->placeholder('e.g. Forest entry tickets, guide arrangements, reporting wild elephant sightings, camping permits')
                                    ->rows(2)
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Description / Visitor Advisory')
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
