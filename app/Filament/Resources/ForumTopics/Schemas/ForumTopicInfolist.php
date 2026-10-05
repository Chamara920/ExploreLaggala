<?php

namespace App\Filament\Resources\ForumTopics\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ForumTopicInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Topic Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Posted By'),

                        TextEntry::make('category.name')
                            ->label('Category'),

                        TextEntry::make('created_at')
                            ->label('Posted At')
                            ->dateTime(),

                        TextEntry::make('views')
                            ->label('Views'),

                        IconEntry::make('is_pinned')
                            ->label('Pinned')
                            ->boolean(),

                        IconEntry::make('is_locked')
                            ->label('Locked')
                            ->boolean(),

                        TextEntry::make('content')
                            ->label('Content')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('Replies')
                    ->schema([
                        TextEntry::make('replies_count')
                            ->label('Total Replies')
                            ->state(fn ($record) => $record->replies()->count()),
                    ]),
            ]);
    }
}
