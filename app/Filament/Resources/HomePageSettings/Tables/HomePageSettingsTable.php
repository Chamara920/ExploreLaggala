<?php

namespace App\Filament\Resources\HomePageSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomePageSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#'),

                TextColumn::make('destinations_count')
                    ->label('Destinations Count'),

                TextColumn::make('blog_posts_count')
                    ->label('Blogs Count'),

                TextColumn::make('news_posts_count')
                    ->label('News Count'),

                TextColumn::make('events_count')
                    ->label('Events Count'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
