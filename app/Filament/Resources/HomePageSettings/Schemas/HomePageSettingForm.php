<?php

namespace App\Filament\Resources\HomePageSettings\Schemas;

use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Event;
use App\Models\NewsPost;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class HomePageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Featured Content Selection (මුල් පිටුව සඳහා තෝරාගැනීම්)')
                    ->description('Select specific items to showcase on the home page. If left blank, the website automatically displays items with the featured flag or latest published.')
                    ->columns(2)
                    ->schema([
                        Select::make('featured_destination_ids')
                            ->label('Featured Destinations (සංචාරක ස්ථාන තෝරන්න)')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return Destination::with('translations')->get()->mapWithKeys(function ($d) {
                                    $name = $d->translationFor('en')?->name
                                        ?? $d->translationFor('si')?->name
                                        ?? 'Destination #'.$d->id;

                                    return [$d->id => "#{$d->id} — {$name}"];
                                });
                            })
                            ->helperText('Selected destinations will appear in the "Featured Destinations" section on the home page.')
                            ->columnSpanFull(),

                        Select::make('featured_blog_post_ids')
                            ->label('Latest Blog Posts (මුල් පිටුවේ පෙන්වන Blog Posts තෝරන්න)')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return BlogPost::with('translations')->where('status', 'published')->get()->mapWithKeys(function ($b) {
                                    $title = $b->translationFor('en')?->title
                                        ?? $b->translationFor('si')?->title
                                        ?? 'Blog #'.$b->id;

                                    return [$b->id => "#{$b->id} — {$title}"];
                                });
                            })
                            ->helperText('Selected posts will appear under "Latest Blog" on the home page.')
                            ->columnSpanFull(),

                        Select::make('featured_news_post_ids')
                            ->label('Latest News Posts (මුල් පිටුවේ පෙන්වන News Posts තෝරන්න)')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return NewsPost::with('translations')->where('status', 'published')->get()->mapWithKeys(function ($n) {
                                    $title = $n->translationFor('en')?->title
                                        ?? $n->translationFor('si')?->title
                                        ?? 'News #'.$n->id;

                                    return [$n->id => "#{$n->id} — {$title}"];
                                });
                            })
                            ->helperText('Selected news will appear under "Latest News" on the home page.')
                            ->columnSpanFull(),

                        Select::make('featured_event_ids')
                            ->label('Latest Events (මුල් පිටුවේ පෙන්වන Events තෝරන්න)')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return Event::with('translations')->where('status', 'published')->get()->mapWithKeys(function ($e) {
                                    $title = $e->translationFor('en')?->title
                                        ?? $e->translationFor('si')?->title
                                        ?? 'Event #'.$e->id;
                                    $date = $e->start_date ? ' ('.$e->start_date->format('Y-m-d').')' : '';

                                    return [$e->id => "#{$e->id} — {$title}{$date}"];
                                });
                            })
                            ->helperText('Selected events will appear under "Latest Events" on the home page.')
                            ->columnSpanFull(),

                        TextInput::make('destinations_count')
                            ->label('Max Destinations Display Count')
                            ->numeric()
                            ->default(6)
                            ->minValue(1)
                            ->maxValue(12),

                        TextInput::make('blog_posts_count')
                            ->label('Max Blog Posts Display Count')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(6),

                        TextInput::make('news_posts_count')
                            ->label('Max News Posts Display Count')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(6),

                        TextInput::make('events_count')
                            ->label('Max Events Display Count')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(6),
                    ]),

                Tabs::make('Section Headings & Text Translations (භාෂා 3 සඳහා මාතෘකා)')
                    ->tabs([
                        Tabs\Tab::make('සිංහල (Sinhala)')
                            ->schema([
                                TextInput::make('translation_si_destinations_title')
                                    ->label('Destinations Section Title (සංචාරක ස්ථාන මාතෘකාව)')
                                    ->placeholder('ප්‍රමුඛ සංචාරක ස්ථාන'),

                                Textarea::make('translation_si_destinations_subtitle')
                                    ->label('Destinations Section Subtitle (විස්තරය)')
                                    ->rows(2),

                                TextInput::make('translation_si_blog_title')
                                    ->label('Blog Section Title (බ්ලොග් අංශයේ මාතෘකාව)')
                                    ->placeholder('නවතම බ්ලොග් සටහන්'),

                                TextInput::make('translation_si_blog_subtitle')
                                    ->label('Blog Section Subtitle')
                                    ->placeholder('සංචාරක කථා සහ මගපෙන්වීම්'),

                                TextInput::make('translation_si_news_title')
                                    ->label('News Section Title (පුවත් අංශයේ මාතෘකාව)')
                                    ->placeholder('නවතම පුවත්'),

                                TextInput::make('translation_si_news_subtitle')
                                    ->label('News Section Subtitle')
                                    ->placeholder('ලග්ගල ප්‍රජා තොරතුරු සහ පුවත්'),

                                TextInput::make('translation_si_events_title')
                                    ->label('Events Section Title (සිදුවීම් අංශයේ මාතෘකාව)')
                                    ->placeholder('ඉදිරි සිදුවීම්'),

                                TextInput::make('translation_si_events_subtitle')
                                    ->label('Events Section Subtitle')
                                    ->placeholder('ලග්ගල ප්‍රදේශයේ ඉදිරි ක්‍රියාකාරකම්'),

                                TextInput::make('translation_si_weather_title')
                                    ->label('Weather & Safety Banner Title')
                                    ->placeholder('නකල්ස් කඳුවැටියේ සංචාරය කිරීමට සැලසුම් කරනවාද?'),

                                Textarea::make('translation_si_weather_subtitle')
                                    ->label('Weather & Safety Banner Subtitle')
                                    ->rows(2),
                            ]),

                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('translation_en_destinations_title')
                                    ->label('Destinations Section Title')
                                    ->placeholder('Featured Destinations'),

                                Textarea::make('translation_en_destinations_subtitle')
                                    ->label('Destinations Section Subtitle')
                                    ->rows(2),

                                TextInput::make('translation_en_blog_title')
                                    ->label('Blog Section Title')
                                    ->placeholder('Latest Blog'),

                                TextInput::make('translation_en_blog_subtitle')
                                    ->label('Blog Section Subtitle')
                                    ->placeholder('Stories & traveler guides'),

                                TextInput::make('translation_en_news_title')
                                    ->label('News Section Title')
                                    ->placeholder('Latest News'),

                                TextInput::make('translation_en_news_subtitle')
                                    ->label('News Section Subtitle')
                                    ->placeholder('Laggala community updates'),

                                TextInput::make('translation_en_events_title')
                                    ->label('Events Section Title')
                                    ->placeholder('Latest Events'),

                                TextInput::make('translation_en_events_subtitle')
                                    ->label('Events Section Subtitle')
                                    ->placeholder('What\'s happening in Laggala'),

                                TextInput::make('translation_en_weather_title')
                                    ->label('Weather & Safety Banner Title')
                                    ->placeholder('Planning a Hike in the Knuckles Range?'),

                                Textarea::make('translation_en_weather_subtitle')
                                    ->label('Weather & Safety Banner Subtitle')
                                    ->rows(2),
                            ]),

                        Tabs\Tab::make('தமிழ் (Tamil)')
                            ->schema([
                                TextInput::make('translation_ta_destinations_title')
                                    ->label('Destinations Section Title')
                                    ->placeholder('முக்கிய சுற்றுலா தலங்கள்'),

                                Textarea::make('translation_ta_destinations_subtitle')
                                    ->label('Destinations Section Subtitle')
                                    ->rows(2),

                                TextInput::make('translation_ta_blog_title')
                                    ->label('Blog Section Title')
                                    ->placeholder('சமீபத்திய வலைப்பதிவு'),

                                TextInput::make('translation_ta_blog_subtitle')
                                    ->label('Blog Section Subtitle')
                                    ->placeholder('கதைகள் மற்றும் பயண வழிகாட்டிகள்'),

                                TextInput::make('translation_ta_news_title')
                                    ->label('News Section Title')
                                    ->placeholder('சமீபத்திய செய்திகள்'),

                                TextInput::make('translation_ta_news_subtitle')
                                    ->label('News Section Subtitle')
                                    ->placeholder('லக்கல சமூக செய்திகள்'),

                                TextInput::make('translation_ta_events_title')
                                    ->label('Events Section Title')
                                    ->placeholder('வரவிருக்கும் நிகழ்வுகள்'),

                                TextInput::make('translation_ta_events_subtitle')
                                    ->label('Events Section Subtitle')
                                    ->placeholder('லக்கலவில் என்ன நடக்கிறது'),

                                TextInput::make('translation_ta_weather_title')
                                    ->label('Weather & Safety Banner Title')
                                    ->placeholder('நக்கிள்ஸ் மலைத்தொடரில் மலையேற்றம் செய்ய திட்டமிடுகிறீர்களா?'),

                                Textarea::make('translation_ta_weather_subtitle')
                                    ->label('Weather & Safety Banner Subtitle')
                                    ->rows(2),
                            ]),
                    ]),
            ]);
    }
}
