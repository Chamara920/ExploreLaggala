<?php

namespace App\Filament\Resources\Destinations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->default('draft')
                            ->required(),

                        Toggle::make('featured')
                            ->label('Featured Destination')
                            ->default(false),

                        TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->placeholder('Example: 7.5000000'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->placeholder('Example: 80.5000000'),
                    ]),

                Tabs::make('Translations')
                    ->tabs([
                        Tabs\Tab::make('Sinhala')
                            ->schema([
                                TextInput::make('translation_si_name')
                                    ->label('Name')
                                    ->required(),

                                TextInput::make('translation_si_slug')
                                    ->label('Slug')
                                    ->required(),

                                Textarea::make('translation_si_short_description')
                                    ->label('Short Description')
                                    ->rows(3),

                                RichEditor::make('translation_si_description')
                                    ->label('Description'),

                                TextInput::make('translation_si_location_name')
                                    ->label('Location Name'),
                            ]),

                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('translation_en_name')
                                    ->label('Name')
                                    ->required(),

                                TextInput::make('translation_en_slug')
                                    ->label('Slug')
                                    ->required(),

                                Textarea::make('translation_en_short_description')
                                    ->label('Short Description')
                                    ->rows(3),

                                RichEditor::make('translation_en_description')
                                    ->label('Description'),

                                TextInput::make('translation_en_location_name')
                                    ->label('Location Name'),
                            ]),

                        Tabs\Tab::make('Tamil')
                            ->schema([
                                TextInput::make('translation_ta_name')
                                    ->label('Name'),

                                TextInput::make('translation_ta_slug')
                                    ->label('Slug'),

                                Textarea::make('translation_ta_short_description')
                                    ->label('Short Description')
                                    ->rows(3),

                                RichEditor::make('translation_ta_description')
                                    ->label('Description'),

                                TextInput::make('translation_ta_location_name')
                                    ->label('Location Name'),
                            ]),
                    ]),

                Section::make('Destination Images')
                    ->schema([
                        Repeater::make('images')
                            ->relationship()
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('destinations')
                                    ->required(),

                                TextInput::make('caption')
                                    ->label('Caption')
                                    ->maxLength(255),

                                TextInput::make('sort_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                Toggle::make('is_cover')
                                    ->label('Cover Image')
                                    ->default(false),
                            ])
                            ->columns(2)
                            ->orderable('sort_order')
                            ->collapsible()
                            ->cloneable(false)
                            ->addActionLabel('Add Destination Image'),
                    ]),

                Section::make('Trip Planner')
                    ->description('Configure this destination for the Smart Trip Planner.')
                    ->schema([

                        /*
                         * Destination Interests
                         */
                        Select::make('interests')
                            ->label('Interests')
                            ->relationship(
                                name: 'interests',
                                titleAttribute: 'name',
                            )
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText(
                                'Select all interests that match this destination.'
                            )
                            ->columnSpanFull(),

                        /*
                         * Planner Details
                         */
                        Section::make('Planner Details')
                            ->relationship('plannerDetails')
                            ->schema([
                                /*
                                 * Planner Basics
                                 */
                                TextInput::make('visit_duration_minutes')
                                    ->label('Visit Duration')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->required()
                                    ->suffix('minutes')
                                    ->helperText(
                                        'Estimated time visitors normally spend at this destination.'
                                    ),

                                Select::make('difficulty')
                                    ->label('Trail / Access Difficulty')
                                    ->options([
                                        'easy' => 'Easy (Family friendly)',
                                        'moderate' => 'Moderate (Some climbing / stairs)',
                                        'difficult' => 'Difficult (Rugged terrain / Trekking)',
                                    ])
                                    ->required()
                                    ->default('easy'),

                                TimePicker::make('opening_time')
                                    ->label('Opening Time')
                                    ->seconds(false),

                                TimePicker::make('closing_time')
                                    ->label('Closing Time')
                                    ->seconds(false),

                                TextInput::make('entry_fee')
                                    ->label('Entry Fee')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('LKR')
                                    ->helperText(
                                        'Leave empty when there is no fixed entry fee.'
                                    ),

                                Toggle::make('planner_enabled')
                                    ->label('Enable in Smart Trip Planner')
                                    ->default(true)
                                    ->helperText(
                                        'Disable this if the destination should not be recommended automatically.'
                                    ),

                                /*
                                 * Public Transport & Road Logistics (For Smart Trip Planner)
                                 */
                                TextInput::make('nearest_bus_stop')
                                    ->label('Nearest Bus Stop / Stand')
                                    ->placeholder('e.g. Pallegama Central Bus Stand, Riverston Gap, Illukkumbura')
                                    ->helperText('Closest location serviced by public buses.')
                                    ->columnSpanFull(),

                                TextInput::make('bus_routes')
                                    ->label('Public Bus Routes & Numbers')
                                    ->placeholder('e.g. Route 702 (Matale - Laggala), Matale - Pallegama via Rattota')
                                    ->helperText('Bus route names/numbers operating towards this area.')
                                    ->columnSpanFull(),

                                Select::make('transport_accessibility')
                                    ->label('Public Transport Accessibility')
                                    ->options([
                                        'direct_bus' => 'Direct Public Bus Access (Walking distance from stop)',
                                        'short_walk' => 'Short Walk from Bus Route (500m - 1.5km)',
                                        'tuk_tuk_from_bus' => 'Tuk-Tuk / Taxi Needed from Bus Stop',
                                        'four_wheel_only' => '4WD / Off-Road Vehicle Required (No buses)',
                                        'trail_walk_only' => 'Footpath / Hiking Trail Only (Walking only)',
                                    ])
                                    ->default('direct_bus'),

                                Select::make('recommended_vehicle')
                                    ->label('Recommended Vehicle Access')
                                    ->options([
                                        'any_vehicle' => 'All Vehicles (Cars, Vans, Buses)',
                                        'high_clearance' => 'High Clearance Recommended (SUVs, Vans)',
                                        'four_wheel' => '4WD / Off-Road Vehicles Only',
                                        'bike_foot' => 'Motorbike or Foot / Trekking Only',
                                    ])
                                    ->default('any_vehicle'),

                                Toggle::make('parking_available')
                                    ->label('Vehicle Parking Available')
                                    ->default(true)
                                    ->columnSpanFull(),

                                /*
                                 * Mobile Network Coverage & Connectivity (For Mobile Coverage & Safety)
                                 */
                                Select::make('mobile_signal_level')
                                    ->label('Mobile Signal Strength')
                                    ->options([
                                        'good' => 'Good Signal (Strong 4G / Calls across major networks)',
                                        'partial' => 'Partial / Weak Signal (Intermittent 3G/2G or specific vantage spots)',
                                        'poor' => 'Poor Signal (Sporadic emergency calls only)',
                                        'no_signal' => 'No Signal / Blackout Zone (Zero cellular coverage)',
                                    ])
                                    ->default('good')
                                    ->helperText('Vital for hiker safety and trip planning in Knuckles & Laggala.')
                                    ->columnSpanFull(),

                                TextInput::make('best_mobile_networks')
                                    ->label('Best Available Network Operator(s)')
                                    ->placeholder('e.g. Dialog (4G), Mobitel (2G only), Hutch (No signal)')
                                    ->helperText('Networks with usable coverage at this location.')
                                    ->columnSpanFull(),

                                Textarea::make('connectivity_notes')
                                    ->label('Mobile Network Advisory')
                                    ->placeholder('e.g. Cellular signal drops 1km past the entrance. Please download offline maps in advance.')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Textarea::make('notes')
                                    ->label('General Planner Notes')
                                    ->rows(3)
                                    ->maxLength(2000)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        /*
                         * Mobile Coverage Reports (Synchronizes directly with /plan/mobile-coverage)
                         */
                        Section::make('Mobile Network Coverage Reports (Live Community Map)')
                            ->description('Record specific operator coverage observations for this spot. These appear on the /plan/mobile-coverage map.')
                            ->schema([
                                Repeater::make('mobileCoverageReports')
                                    ->relationship('mobileCoverageReports')
                                    ->schema([
                                        Select::make('network_operator')
                                            ->label('Operator')
                                            ->options([
                                                'dialog' => 'Dialog',
                                                'mobitel' => 'Mobitel',
                                                'hutch' => 'Hutch',
                                                'airtel' => 'Airtel',
                                                'multiple' => 'Multiple Networks',
                                                'other' => 'Other',
                                            ])
                                            ->required(),

                                        Select::make('coverage_type')
                                            ->label('Technology')
                                            ->options([
                                                '4g' => '4G LTE',
                                                '3g' => '3G',
                                                '2g' => '2G (Voice / SMS)',
                                                '5g' => '5G',
                                                'no_signal' => 'No Signal',
                                            ])
                                            ->default('4g')
                                            ->required(),

                                        Select::make('signal_strength')
                                            ->label('Signal Level')
                                            ->options([
                                                'excellent' => 'Excellent',
                                                'good' => 'Good',
                                                'fair' => 'Fair',
                                                'poor' => 'Poor',
                                                'none' => 'None',
                                            ])
                                            ->default('good')
                                            ->required(),

                                        TextInput::make('description')
                                            ->label('Notes')
                                            ->placeholder('e.g. Stable reception at viewpoint')
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(3)
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Operator Coverage Report')
                                    ->columnSpanFull(),
                            ]),

                        /*
                         * Best Time / Seasonal Suitability
                         */
                        Repeater::make('seasons')
                            ->label('Best Time / Seasonal Suitability')
                            ->relationship()
                            ->schema([
                                Select::make('month')
                                    ->label('Month')
                                    ->options([
                                        1 => 'January',
                                        2 => 'February',
                                        3 => 'March',
                                        4 => 'April',
                                        5 => 'May',
                                        6 => 'June',
                                        7 => 'July',
                                        8 => 'August',
                                        9 => 'September',
                                        10 => 'October',
                                        11 => 'November',
                                        12 => 'December',
                                    ])
                                    ->required()
                                    ->distinct(),

                                Select::make('rating')
                                    ->label('Suitability')
                                    ->options([
                                        'best' => 'Best',
                                        'suitable' => 'Suitable',
                                        'not_recommended' => 'Not Recommended',
                                    ])
                                    ->required(),

                                TextInput::make('note')
                                    ->label('Seasonal Note')
                                    ->maxLength(500)
                                    ->columnSpanFull()
                                    ->helperText(
                                        'Optional note about weather, accessibility, water level, etc.'
                                    ),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Month')
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                if (! isset($state['month'])) {
                                    return 'Season';
                                }

                                $months = [
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December',
                                ];

                                return $months[(int) $state['month']] ?? 'Season';
                            })
                            ->columnSpanFull(),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
