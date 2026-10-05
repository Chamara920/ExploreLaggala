<?php

namespace App\Filament\Resources\ServicePlaceReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServicePlaceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),

                TextInput::make('rating')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required(),

                Textarea::make('comment')
                    ->rows(4)
                    ->required(),

                Textarea::make('admin_note')
                    ->label('Admin Note (Internal)')
                    ->rows(2),
            ]);
    }
}
