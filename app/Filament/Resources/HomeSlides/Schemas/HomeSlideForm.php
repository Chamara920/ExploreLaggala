<?php

namespace App\Filament\Resources\HomeSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class HomeSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slide Image & Links')
                    ->description('Configure background image, status, display order, and button target links.')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Slide Image (Upload)')
                            ->disk('public')
                            ->directory('home-slides')
                            ->image()
                            ->columnSpanFull(),

                        TextInput::make('image_url')
                            ->label('Or External Image URL')
                            ->placeholder('https://images.unsplash.com/...')
                            ->helperText('Used if no uploaded image file is provided above.')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active (Show on Home Page)')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0),

                        TextInput::make('primary_button_url')
                            ->label('Primary Button URL')
                            ->default('/explore/destinations')
                            ->required(),

                        TextInput::make('secondary_button_url')
                            ->label('Secondary Button URL (Optional)')
                            ->placeholder('/explore/map'),

                        TextInput::make('planner_button_url')
                            ->label('Smart Trip Planner Button URL')
                            ->default('/plan/travel-guide')
                            ->columnSpanFull(),
                    ]),

                Tabs::make('Multilingual Content (භාෂා 3 සඳහා අන්තර්ගතය)')
                    ->tabs([
                        Tabs\Tab::make('සිංහල (Sinhala)')
                            ->schema([
                                TextInput::make('translation_si_badge')
                                    ->label('Badge / Tag')
                                    ->placeholder('උදා: ලග්ගල ගවේෂණය • නකල්ස් කඳුවැටිය'),

                                TextInput::make('translation_si_title')
                                    ->label('Hero Title (ප්‍රධාන මාතෘකාව)')
                                    ->required()
                                    ->placeholder('උදා: ලග්ගල හරිත හදවත සොයා යන්න'),

                                Textarea::make('translation_si_subtitle')
                                    ->label('Description / Subtitle (විස්තරය)')
                                    ->rows(3),

                                TextInput::make('translation_si_primary_button_text')
                                    ->label('Primary Button Text')
                                    ->placeholder('උදා: සංචාරක ස්ථාන ගවේෂණය'),

                                TextInput::make('translation_si_secondary_button_text')
                                    ->label('Secondary Button Text')
                                    ->placeholder('උදා: සිතියම බලන්න'),

                                TextInput::make('translation_si_planner_button_text')
                                    ->label('Planner Button Title')
                                    ->placeholder('උදා: ස්මාර්ට් චාරිකා සැලසුම්කරු'),

                                TextInput::make('translation_si_planner_button_subtitle')
                                    ->label('Planner Button Subtitle')
                                    ->placeholder('උදා: ඔබේ ගමන සැලසුම් කරන්න'),
                            ]),

                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('translation_en_badge')
                                    ->label('Badge / Tag')
                                    ->placeholder('e.g. Explore Laggala • Knuckles Range'),

                                TextInput::make('translation_en_title')
                                    ->label('Hero Title')
                                    ->required()
                                    ->placeholder('e.g. Discover the Green Heart of Laggala'),

                                Textarea::make('translation_en_subtitle')
                                    ->label('Description / Subtitle')
                                    ->rows(3),

                                TextInput::make('translation_en_primary_button_text')
                                    ->label('Primary Button Text')
                                    ->placeholder('e.g. Explore Destinations'),

                                TextInput::make('translation_en_secondary_button_text')
                                    ->label('Secondary Button Text')
                                    ->placeholder('e.g. Interactive Map'),

                                TextInput::make('translation_en_planner_button_text')
                                    ->label('Planner Button Title')
                                    ->placeholder('e.g. Smart Trip Planner'),

                                TextInput::make('translation_en_planner_button_subtitle')
                                    ->label('Planner Button Subtitle')
                                    ->placeholder('e.g. Plan Your Journey'),
                            ]),

                        Tabs\Tab::make('தமிழ் (Tamil)')
                            ->schema([
                                TextInput::make('translation_ta_badge')
                                    ->label('Badge / Tag')
                                    ->placeholder('உதா: லக்கல ஆய்வு • நக்கிள்ஸ் மலைத்தொடர்'),

                                TextInput::make('translation_ta_title')
                                    ->label('Hero Title')
                                    ->placeholder('உதா: லக்கலவின் பசுமை இதயத்தைக் கண்டறியுங்கள்'),

                                Textarea::make('translation_ta_subtitle')
                                    ->label('Description / Subtitle')
                                    ->rows(3),

                                TextInput::make('translation_ta_primary_button_text')
                                    ->label('Primary Button Text')
                                    ->placeholder('உதா: சுற்றுலா தலங்களை ஆராயுங்கள்'),

                                TextInput::make('translation_ta_secondary_button_text')
                                    ->label('Secondary Button Text')
                                    ->placeholder('உதா: வரைபடம்'),

                                TextInput::make('translation_ta_planner_button_text')
                                    ->label('Planner Button Title')
                                    ->placeholder('உதா: ஸ்மார்ட் பயணத் திட்டமிடுபவர்'),

                                TextInput::make('translation_ta_planner_button_subtitle')
                                    ->label('Planner Button Subtitle')
                                    ->placeholder('உதா: உங்கள் பயணத்தைத் திட்டமிடுங்கள்'),
                            ]),
                    ]),
            ]);
    }
}
