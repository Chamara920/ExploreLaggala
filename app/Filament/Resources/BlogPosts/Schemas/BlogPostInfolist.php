<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Author'),

                        TextEntry::make('category.name')
                            ->label('Category'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'published' => 'success',
                                'pending_review' => 'warning',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),

                        IconEntry::make('featured')
                            ->label('Featured')
                            ->boolean(),

                        TextEntry::make('published_at')
                            ->label('Published At')
                            ->dateTime(),

                        TextEntry::make('reviewer.name')
                            ->label('Reviewed By'),

                        TextEntry::make('reviewed_at')
                            ->label('Reviewed At')
                            ->dateTime(),

                        TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($record) => $record?->status === 'rejected')
                            ->columnSpanFull(),

                        ImageEntry::make('cover_image')
                            ->label('Cover Image')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
