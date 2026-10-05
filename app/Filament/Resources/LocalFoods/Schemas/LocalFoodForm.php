<?php

namespace App\Filament\Resources\LocalFoods\Schemas;

use App\Models\StayEatCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class LocalFoodForm
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
                            ->default('published')
                            ->required(),

                        Select::make('category_id')
                            ->label('Food Category')
                            ->options(function () {
                                return StayEatCategory::query()
                                    ->where('section', 'local-food')
                                    ->where('is_active', true)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->nullable()
                            ->helperText('E.g. Traditional Meals, Village Food, Local Snacks, Traditional Drinks, Food Experiences'),

                        Toggle::make('featured')
                            ->label('Featured Local Food / Experience')
                            ->default(false),

                        TextInput::make('price_range')
                            ->label('Price / Cost')
                            ->placeholder('e.g. LKR 300 - 1,000 or Free tasting'),

                        TextInput::make('phone')
                            ->label('Contact / Host Phone')
                            ->tel()
                            ->placeholder('066 227 xxxx / 07x xxx xxxx'),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email(),

                        TextInput::make('website')
                            ->label('Website / Info Link')
                            ->url(),

                        TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->placeholder('Example: 7.5000000')
                            ->helperText('Optional: Provide coordinates to display on the map'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->placeholder('Example: 80.5000000')
                            ->helperText('Optional: Provide coordinates to display on the map'),
                    ]),

                Tabs::make('Translations')
                    ->tabs([
                        Tabs\Tab::make('Sinhala (සිංහල)')
                            ->schema([
                                TextInput::make('translation_si_title')
                                    ->label('Title / නම')
                                    ->required(),

                                TextInput::make('translation_si_slug')
                                    ->label('Slug / යොමුව')
                                    ->required(),

                                TextInput::make('translation_si_location_name')
                                    ->label('Village / Location / ගම හෝ ස්ථානය'),

                                TextInput::make('translation_si_opening_hours')
                                    ->label('Availability / වේලාවන් හෝ ලබා ගත හැකි කාලය')
                                    ->placeholder('Morning & Lunch hours / Seasonal'),

                                Textarea::make('translation_si_short_description')
                                    ->label('Short Description / කෙටි හැඳින්වීම')
                                    ->rows(3),

                                RichEditor::make('translation_si_description')
                                    ->label('Full Story, Ingredients & Experience / සවිස්තරාත්මක විස්තරය'),
                            ]),

                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('translation_en_title')
                                    ->label('Title / Name')
                                    ->required(),

                                TextInput::make('translation_en_slug')
                                    ->label('Slug')
                                    ->required(),

                                TextInput::make('translation_en_location_name')
                                    ->label('Village / Location'),

                                TextInput::make('translation_en_opening_hours')
                                    ->label('Availability / Hours')
                                    ->placeholder('Morning & Lunch hours / Seasonal'),

                                Textarea::make('translation_en_short_description')
                                    ->label('Short Description')
                                    ->rows(3),

                                RichEditor::make('translation_en_description')
                                    ->label('Full Story, Ingredients & Experience'),
                            ]),

                        Tabs\Tab::make('Tamil (தமிழ்)')
                            ->schema([
                                TextInput::make('translation_ta_title')
                                    ->label('Title / பெயர்'),

                                TextInput::make('translation_ta_slug')
                                    ->label('Slug'),

                                TextInput::make('translation_ta_location_name')
                                    ->label('Location / இடம்'),

                                TextInput::make('translation_ta_opening_hours')
                                    ->label('Hours / கிடைக்கும் நேரம்'),

                                Textarea::make('translation_ta_short_description')
                                    ->label('Short Description / சுருக்கம்')
                                    ->rows(3),

                                RichEditor::make('translation_ta_description')
                                    ->label('Full Description / முழு விளக்கம்'),
                            ]),
                    ]),

                Section::make('Photos & Gallery (Max 5 Images)')
                    ->description('Upload up to 5 photos. If multiple images are provided, an interactive slider will be displayed at the top of the detail page.')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image File')
                                    ->image()
                                    ->disk('public')
                                    ->directory('stay-eat/local-food')
                                    ->required(),

                                TextInput::make('caption')
                                    ->label('Image Caption')
                                    ->maxLength(255),

                                TextInput::make('sort_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                Toggle::make('is_cover')
                                    ->label('Cover / Thumbnail Image')
                                    ->default(false),
                            ])
                            ->columns(2)
                            ->maxItems(5)
                            ->orderable('sort_order')
                            ->collapsible()
                            ->cloneable(false)
                            ->addActionLabel('Add Photo (Max 5)'),
                    ]),
            ]);
    }
}
