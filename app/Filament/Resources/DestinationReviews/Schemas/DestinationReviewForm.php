<?php

namespace App\Filament\Resources\DestinationReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DestinationReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Review')
                    ->schema([

                        TextInput::make('destination_name')
                            ->label('Destination')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                return $record?->destination
                                    ?->translationFor('en')
                                    ?->name;
                            }),

                        TextInput::make('user_name')
                            ->label('User')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                return $record?->user?->name;
                            }),

                        Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '★☆☆☆☆ — 1',
                                2 => '★★☆☆☆ — 2',
                                3 => '★★★☆☆ — 3',
                                4 => '★★★★☆ — 4',
                                5 => '★★★★★ — 5',
                            ])
                            ->required(),

                        Textarea::make('comment')
                            ->label('Review')
                            ->rows(6)
                            ->maxLength(2000),

                        Select::make('status')
                            ->label('Review Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),

                        Textarea::make('admin_note')
                            ->label('Admin Note')
                            ->rows(4)
                            ->maxLength(2000),

                    ])
                    ->columns(2),
            ]);
    }
}
