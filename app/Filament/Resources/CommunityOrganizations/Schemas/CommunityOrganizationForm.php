<?php

namespace App\Filament\Resources\CommunityOrganizations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommunityOrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Organization Basic Details')
                    ->columns(2)
                    ->schema([
                        Select::make('type_id')
                            ->label('Organization Type')
                            ->relationship('organizationType', 'name')
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

                        TextInput::make('registration_number')
                            ->label('Registration Number')
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Contact Phone')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Contact Email')
                            ->email()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('website')
                            ->label('Website URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('facebook_url')
                            ->label('Facebook Page URL')
                            ->url()
                            ->maxLength(255),

                        FileUpload::make('logo')
                            ->directory('organizations/logos')
                            ->image()
                            ->label('Organization Logo'),

                        FileUpload::make('cover_image')
                            ->directory('organizations/covers')
                            ->image()
                            ->label('Cover Photo'),

                        Toggle::make('featured')
                            ->label('Featured Organization'),

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

                                TextInput::make('name')
                                    ->label('Organization Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('summary')
                                    ->label('Short Summary')
                                    ->rows(2),

                                RichEditor::make('description')
                                    ->label('Full Description'),

                                RichEditor::make('services_offered')
                                    ->label('Services Offered'),
                            ])
                            ->collapsible()
                            ->defaultItems(1)
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල (Sinhala) - '.($state['name'] ?? ''),
                                'en' => 'English - '.($state['name'] ?? ''),
                                'ta' => 'தமிழ் (Tamil) - '.($state['name'] ?? ''),
                                default => 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
