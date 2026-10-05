<?php

namespace App\Filament\Resources\WildlifeForestOffices\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WildlifeForestOfficeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Office Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('department_type')->badge(),
                        TextEntry::make('range_area')->placeholder('-'),
                        TextEntry::make('phone'),
                        TextEntry::make('emergency_hotline')->placeholder('-'),
                        TextEntry::make('officer_in_charge_phone')->placeholder('-'),
                        TextEntry::make('city')->placeholder('-'),
                        ImageEntry::make('image')->placeholder('-')->columnSpanFull(),
                    ]),

                Section::make('Translations')
                    ->schema([
                        RepeatableEntry::make('translations')
                            ->schema([
                                TextEntry::make('locale')->badge(),
                                TextEntry::make('name'),
                                TextEntry::make('address')->placeholder('-'),
                                TextEntry::make('duties_description')->placeholder('-')->columnSpanFull(),
                                TextEntry::make('description')->placeholder('-')->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
