<?php

namespace App\Filament\Resources\ServicePlaces\Schemas;

use App\Models\ServicePlace;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ServicePlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        $sectionOptions = [];
        foreach (ServicePlace::SECTIONS as $key => $sec) {
            $sectionOptions[$key] = $sec['title_en'].' ('.$sec['title_si'].')';
        }

        return $schema
            ->components([

                // 1. BASIC DETAILS
                Section::make('General Profile')
                    ->columns(2)
                    ->schema([
                        Select::make('section')
                            ->label('Service Category / සේවා අංශය')
                            ->options($sectionOptions)
                            ->required()
                            ->searchable()
                            ->reactive(),

                        TextInput::make('sub_category')
                            ->label('Sub-category / වර්ගය (e.g. Hospital, Supermarket, ATM, Fuel)')
                            ->placeholder('e.g. hospital / clinic / supermarket / atm / school'),

                        Select::make('status')
                            ->options([
                                'published' => 'Published',
                                'draft' => 'Draft',
                                'archived' => 'Archived',
                            ])
                            ->default('published')
                            ->required(),

                        Toggle::make('featured')
                            ->label('Featured / විශේෂිත')
                            ->default(false),

                        Toggle::make('is_24_hours')
                            ->label('24 Hours Service / පැය 24 පුරා විවෘතයි')
                            ->default(false),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        // Single photo only constraint
                        FileUpload::make('image_path')
                            ->label('Place Photo / ඡායාරූපය (Single Image Only)')
                            ->image()
                            ->disk('public')
                            ->directory('services/places')
                            ->maxFiles(1)
                            ->helperText('Upload a single main photo of the building, shop front, or facility.')
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->label('Telephone / Hotline')
                            ->tel()
                            ->placeholder('066 227 5xxx'),

                        TextInput::make('emergency_hotline')
                            ->label('Emergency Hotline (if applicable)')
                            ->placeholder('e.g. 1990 / direct emergency number'),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email(),

                        TextInput::make('website')
                            ->label('Website / Web Link')
                            ->url(),

                        TextInput::make('latitude')
                            ->label('Latitude (GPS)')
                            ->numeric()
                            ->placeholder('e.g. 7.5265000')
                            ->helperText('Coordinates for displaying interactive map location'),

                        TextInput::make('longitude')
                            ->label('Longitude (GPS)')
                            ->numeric()
                            ->placeholder('e.g. 80.7431000')
                            ->helperText('Coordinates for displaying interactive map location'),
                    ]),

                // 2. MULTILINGUAL TRANSLATIONS (SINHALA, ENGLISH, TAMIL)
                Tabs::make('Translations')
                    ->tabs([
                        Tabs\Tab::make('Sinhala (සිංහල)')
                            ->schema([
                                TextInput::make('translation_si_name')
                                    ->label('Place Name / ආයතනයේ හෝ ස්ථානයේ නම')
                                    ->required(),

                                TextInput::make('translation_si_slug')
                                    ->label('Slug / යොමුව')
                                    ->required(),

                                TextInput::make('translation_si_location_name')
                                    ->label('Address / ස්ථානය හෝ ලිපිනය')
                                    ->placeholder('පල්ලේගම, ලග්ගල'),

                                TextInput::make('translation_si_operating_hours')
                                    ->label('Opening Hours / විවෘත වේලාවන්')
                                    ->placeholder('සඳුදා - ඉරිදා: පෙ.ව. 8:00 - ප.ව. 8:00'),

                                Textarea::make('translation_si_key_facilities')
                                    ->label('Key Services & Facilities / විශේෂ සේවාවන් සහ පහසුකම්')
                                    ->rows(2)
                                    ->placeholder('Emergency care, OPD, In-ward care, Laboratory, ECG'),

                                Textarea::make('translation_si_short_description')
                                    ->label('Short Summary / කෙටි හැඳින්වීම')
                                    ->rows(3),

                                RichEditor::make('translation_si_description')
                                    ->label('Full Description / සවිස්තරාත්මක තොරතුරු'),
                            ]),

                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('translation_en_name')
                                    ->label('Place / Business Name')
                                    ->required(),

                                TextInput::make('translation_en_slug')
                                    ->label('Slug')
                                    ->required(),

                                TextInput::make('translation_en_location_name')
                                    ->label('Address / Location')
                                    ->placeholder('Pallegama, Laggala'),

                                TextInput::make('translation_en_operating_hours')
                                    ->label('Operating Hours')
                                    ->placeholder('Mon - Sun: 8:00 AM - 8:00 PM'),

                                Textarea::make('translation_en_key_facilities')
                                    ->label('Key Services & Facilities')
                                    ->rows(2),

                                Textarea::make('translation_en_short_description')
                                    ->label('Short Summary')
                                    ->rows(3),

                                RichEditor::make('translation_en_description')
                                    ->label('Full Description'),
                            ]),

                        Tabs\Tab::make('Tamil (தமிழ்)')
                            ->schema([
                                TextInput::make('translation_ta_name')
                                    ->label('Name / பெயர்'),

                                TextInput::make('translation_ta_slug')
                                    ->label('Slug'),

                                TextInput::make('translation_ta_location_name')
                                    ->label('Address / முகவரி'),

                                TextInput::make('translation_ta_operating_hours')
                                    ->label('Operating Hours / அலுவலக நேரம்'),

                                Textarea::make('translation_ta_key_facilities')
                                    ->label('Facilities / வசதிகள்')
                                    ->rows(2),

                                Textarea::make('translation_ta_short_description')
                                    ->label('Short Summary / சுருக்கம்')
                                    ->rows(3),

                                RichEditor::make('translation_ta_description')
                                    ->label('Full Description / முழு விளக்கம்'),
                            ]),
                    ]),

            ]);
    }
}
