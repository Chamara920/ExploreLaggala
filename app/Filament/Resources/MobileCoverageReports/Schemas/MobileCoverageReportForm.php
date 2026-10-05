<?php

namespace App\Filament\Resources\MobileCoverageReports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MobileCoverageReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coverage Report Details')
                    ->columns(2)
                    ->schema([
                        Select::make('destination_id')
                            ->label('Destination')
                            ->relationship('destination', 'name')
                            ->searchable()
                            ->nullable(),

                        TextInput::make('location_name')
                            ->label('Location Name (English Fallback)')
                            ->required(),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric(),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric(),

                        Select::make('network_operator')
                            ->label('Network Operator')
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
                            ->label('Coverage Type')
                            ->options([
                                '2g' => '2G',
                                '3g' => '3G',
                                '4g' => '4G',
                                '5g' => '5G',
                                'no_signal' => 'No Signal',
                            ])
                            ->required(),

                        Select::make('signal_strength')
                            ->label('Signal Strength')
                            ->options([
                                'excellent' => 'Excellent',
                                'good' => 'Good',
                                'fair' => 'Fair',
                                'poor' => 'Poor',
                                'none' => 'None',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),

                        Select::make('reported_by')
                            ->label('Reported By')
                            ->relationship('reporter', 'name')
                            ->searchable()
                            ->required(),

                        DateTimePicker::make('reported_at')
                            ->label('Reported At')
                            ->required(),

                        Textarea::make('description')
                            ->label('Description (English Fallback)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Translations (භාෂා ත්‍රිත්වය — සිංහල / English / தமிழ்)')
                    ->description('Add location name and description in each language.')
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

                                TextInput::make('location_name')
                                    ->label('Location / ස්ථානය / இடம்')
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->label('Description / විස්තරය / விளக்கம்')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => match ($state['locale'] ?? null) {
                                'si' => 'සිංහල — '.($state['location_name'] ?? 'ස්ථානය'),
                                'en' => 'English — '.($state['location_name'] ?? 'Location'),
                                'ta' => 'தமிழ் — '.($state['location_name'] ?? 'இடம்'),
                                default => $state['location_name'] ?? 'Translation',
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
