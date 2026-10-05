<?php

namespace App\Filament\Resources\ExploreItemReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExploreItemReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review Details')
                    ->schema([
                        TextInput::make('item_title')
                            ->label('Explore Item')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                return $record?->exploreItem?->translationFor('si')?->title
                                    ?? $record?->exploreItem?->translationFor('en')?->title
                                    ?? '—';
                            }),

                        TextInput::make('user_name')
                            ->label('Reviewer')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($record) => $record?->user?->name),

                        Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '★☆☆☆☆ — 1 Star',
                                2 => '★★☆☆☆ — 2 Stars',
                                3 => '★★★☆☆ — 3 Stars',
                                4 => '★★★★☆ — 4 Stars',
                                5 => '★★★★★ — 5 Stars',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved (Visible to Public)',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),

                        Textarea::make('comment')
                            ->label('Comment / Review Text')
                            ->rows(5)
                            ->maxLength(2000)
                            ->columnSpanFull(),

                        Textarea::make('admin_note')
                            ->label('Admin Moderation Note')
                            ->rows(3)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
