<?php

namespace App\Filament\Resources\EmergencyContacts\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmergencyContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Details')
                    ->columns(2)
                    ->schema([
                        Select::make('category')
                            ->label('Category')
                            ->options([
                                'hotline' => 'Emergency Hotline (General)',
                                'medical' => 'Medical / Ambulance',
                                'police' => 'Police & Security',
                                'disaster' => 'Disaster Management',
                                'utility' => 'Public Utilities (CEB / Water)',
                                'local' => 'Local Authorities (DS / PS)',
                            ])
                            ->default('hotline')
                            ->required(),

                        TextInput::make('short_code')
                            ->label('Quick Dial / Short Code')
                            ->placeholder('e.g. 119, 1990, 117')
                            ->maxLength(20),

                        TextInput::make('phone_number')
                            ->label('Primary Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        TextInput::make('alternate_phone')
                            ->label('Alternate Phone')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255),

                        Toggle::make('is_toll_free')
                            ->label('Toll Free (ගාස්තු රහිතයි)')
                            ->default(false),

                        Toggle::make('is_24x7')
                            ->label('24/7 Available (පැය 24 පුරා)')
                            ->default(true),

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
                    ->description('Add content in Sinhala (si), English (en), and Tamil (ta)')
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
                                    ->label('Name / Service Title')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('department')
                                    ->label('Department / Agency')
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->label('Description / Notes')
                                    ->rows(2)
                                    ->columnSpanFull(),

                                TextInput::make('address')
                                    ->label('Office Address (if applicable)')
                                    ->maxLength(255)
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
