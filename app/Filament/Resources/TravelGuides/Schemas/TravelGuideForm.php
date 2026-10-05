<?php

namespace App\Filament\Resources\TravelGuides\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TravelGuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('category')
                            ->options([
                                'getting_here' => 'Getting Here',
                                'accommodation' => 'Accommodation',
                                'food_drink' => 'Food & Drink',
                                'safety_tips' => 'Safety Tips',
                                'cultural_etiquette' => 'Cultural Etiquette',
                                'packing_list' => 'Packing List',
                                'best_time_to_visit' => 'Best Time to Visit',
                                'local_customs' => 'Local Customs',
                                'transportation' => 'Transportation',
                                'money_budget' => 'Money & Budget',
                                'health_medical' => 'Health & Medical',
                                'general' => 'General Guide',
                            ])
                            ->required()
                            ->default('general'),

                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),

                        Select::make('author_id')
                            ->relationship('author', 'name')
                            ->default(auth()->id())
                            ->required(),

                        DateTimePicker::make('published_at')
                            ->default(now()),

                        Toggle::make('featured')
                            ->label('Featured Guide'),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Textarea::make('summary')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Short overview shown on cards and search results (English fallback).'),

                        FileUpload::make('cover_image')
                            ->image()
                            ->directory('travel-guides')
                            ->columnSpanFull(),
                    ]),

                Section::make('Guide Content (English Fallback)')
                    ->schema([
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Default content used as fallback when a translation is unavailable.')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'strike',
                                'underline',
                                'blockquote',
                            ]),
                    ]),

                Section::make('Translations (භාෂා ත්‍රිත්වය — සිංහල / English / தமிழ்)')
                    ->description('Add the guide title, summary, and content in each language.')
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

                                TextInput::make('title')
                                    ->label('Title / මාතෘකාව / தலைப்பு')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('summary')
                                    ->label('Summary / සාරාංශය / சுருக்கம்')
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                RichEditor::make('content')
                                    ->label('Content / අන්තර්ගතය / உள்ளடக்கம்')
                                    ->toolbarButtons([
                                        'bold', 'bulletList', 'h2', 'h3',
                                        'italic', 'link', 'orderedList',
                                        'strike', 'underline', 'blockquote',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල — '.($state['title'] ?? 'නාමය'),
                                'en' => 'English — '.($state['title'] ?? 'Title'),
                                'ta' => 'தமிழ் — '.($state['title'] ?? 'தலைப்பு'),
                                default => $state['title'] ?? 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
