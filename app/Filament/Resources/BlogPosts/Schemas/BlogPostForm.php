<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

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

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending_review' => 'Pending Review',
                                'published' => 'Published',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft')
                            ->required(),

                        Toggle::make('featured')
                            ->label('Featured Post'),

                        DateTimePicker::make('published_at')
                            ->label('Publish Date'),

                        FileUpload::make('cover_image')
                            ->directory('blog/covers')
                            ->image()
                            ->label('Cover Image')
                            ->columnSpanFull(),

                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($get) => $get('status') === 'rejected')
                            ->columnSpanFull(),
                    ]),

                Section::make('Translations (සිංහල / English / தமிழ்)')
                    ->description('Add content in Sinhala (si), English (en), and Tamil (ta)')
                    ->schema([
                        Repeater::make('translations')
                            ->relationship('translations')
                            ->schema([
                                Select::make('locale')
                                    ->label('Language')
                                    ->options([
                                        'si' => 'සිංහල (Sinhala)',
                                        'en' => 'English',
                                        'ta' => 'தமிழ் (Tamil)',
                                    ])
                                    ->required(),

                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('excerpt')
                                    ->label('Short Excerpt')
                                    ->rows(2),

                                RichEditor::make('content')
                                    ->label('Full Blog Content')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->defaultItems(1)
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල (Sinhala) - '.($state['title'] ?? ''),
                                'en' => 'English - '.($state['title'] ?? ''),
                                'ta' => 'தமிழ் (Tamil) - '.($state['title'] ?? ''),
                                default => 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
