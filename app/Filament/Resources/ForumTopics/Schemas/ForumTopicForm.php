<?php

namespace App\Filament\Resources\ForumTopics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ForumTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Forum Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('Question / Topic Title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('content')
                    ->label('Content / Body')
                    ->rows(6)
                    ->required(),

                Toggle::make('is_pinned')
                    ->label('Pinned to Top'),

                Toggle::make('is_locked')
                    ->label('Locked (Prevent new replies)'),
            ]);
    }
}
