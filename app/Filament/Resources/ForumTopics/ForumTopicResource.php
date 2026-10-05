<?php

namespace App\Filament\Resources\ForumTopics;

use App\Filament\Resources\ForumTopics\Pages\CreateForumTopic;
use App\Filament\Resources\ForumTopics\Pages\EditForumTopic;
use App\Filament\Resources\ForumTopics\Pages\ListForumTopics;
use App\Filament\Resources\ForumTopics\Pages\ViewForumTopic;
use App\Filament\Resources\ForumTopics\Schemas\ForumTopicForm;
use App\Filament\Resources\ForumTopics\Schemas\ForumTopicInfolist;
use App\Models\ForumTopic;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ForumTopicResource extends Resource
{
    protected static ?string $model = ForumTopic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'COMMUNITY';

    protected static ?string $navigationLabel = 'Forum Topics';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin'])
            || (auth()->user()?->can('moderate_forum') ?? false);
    }

    public static function form(Schema $schema): Schema
    {
        return ForumTopicForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ForumTopicInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Topic / Question')
                    ->limit(55)
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable(),

                TextColumn::make('replies_count')
                    ->counts('replies')
                    ->label('Replies')
                    ->sortable(),

                IconColumn::make('is_pinned')
                    ->boolean()
                    ->label('Pinned'),

                IconColumn::make('is_locked')
                    ->boolean()
                    ->label('Locked'),

                TextColumn::make('created_at')
                    ->label('Posted At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('toggle_pin')
                    ->label(fn ($record) => $record->is_pinned ? 'Unpin' : 'Pin')
                    ->icon('heroicon-o-bookmark')
                    ->action(fn ($record) => $record->update(['is_pinned' => ! $record->is_pinned])),

                Action::make('toggle_lock')
                    ->label(fn ($record) => $record->is_locked ? 'Unlock' : 'Lock')
                    ->icon('heroicon-o-lock-closed')
                    ->action(fn ($record) => $record->update(['is_locked' => ! $record->is_locked])),

                DeleteAction::make()
                    ->modalHeading('Delete Forum Topic')
                    ->modalDescription('Are you sure you want to delete this forum topic and all its replies? This action cannot be undone.'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListForumTopics::route('/'),
            'create' => CreateForumTopic::route('/create'),
            'view' => ViewForumTopic::route('/{record}'),
            'edit' => EditForumTopic::route('/{record}/edit'),
        ];
    }
}
