<?php

namespace App\Filament\Resources\EmergencyContacts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmergencyContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('category')
                            ->badge(),
                        TextEntry::make('short_code')
                            ->label('Quick Dial')
                            ->placeholder('-'),
                        TextEntry::make('phone_number')
                            ->label('Primary Phone'),
                        TextEntry::make('alternate_phone')
                            ->placeholder('-'),
                        IconEntry::make('is_toll_free')
                            ->boolean(),
                        IconEntry::make('is_24x7')
                            ->label('24/7 Service')
                            ->boolean(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('sort_order')
                            ->numeric(),
                    ]),

                Section::make('Translations')
                    ->schema([
                        RepeatableEntry::make('translations')
                            ->schema([
                                TextEntry::make('locale')->badge(),
                                TextEntry::make('name'),
                                TextEntry::make('department')->placeholder('-'),
                                TextEntry::make('description')->placeholder('-'),
                                TextEntry::make('address')->placeholder('-'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
