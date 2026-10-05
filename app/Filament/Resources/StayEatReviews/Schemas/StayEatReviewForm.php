<?php

namespace App\Filament\Resources\StayEatReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StayEatReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review Details')
                    ->columns(2)
                    ->schema([
                        Select::make('rating')
                            ->label('Rating (Stars)')
                            ->options([
                                1 => '★ 1 Star',
                                2 => '★★ 2 Stars',
                                3 => '★★★ 3 Stars',
                                4 => '★★★★ 4 Stars',
                                5 => '★★★★★ 5 Stars',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('Moderation Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved (Visible Publicly)',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),

                        Textarea::make('comment')
                            ->label('Review Comment')
                            ->rows(4)
                            ->columnSpanFull()
                            ->required(),

                        TextInput::make('admin_note')
                            ->label('Admin Internal Note')
                            ->columnSpanFull()
                            ->placeholder('Optional note visible only to moderators'),
                    ]),
            ]);
    }
}
