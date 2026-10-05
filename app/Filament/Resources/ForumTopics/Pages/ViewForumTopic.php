<?php

namespace App\Filament\Resources\ForumTopics\Pages;

use App\Filament\Resources\ForumTopics\ForumTopicResource;
use App\Models\ForumReply;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Collection;

class ViewForumTopic extends ViewRecord
{
    protected static string $resource = ForumTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            Action::make('togglePin')
                ->label(fn () => $this->record->is_pinned ? 'Unpin Topic' : 'Pin Topic')
                ->icon('heroicon-o-map-pin')
                ->color('warning')
                ->action(function () {
                    $this->record->update(['is_pinned' => ! $this->record->is_pinned]);
                    Notification::make()
                        ->title($this->record->is_pinned ? 'Topic Pinned' : 'Topic Unpinned')
                        ->success()
                        ->send();
                    $this->refreshRecord();
                }),

            Action::make('toggleLock')
                ->label(fn () => $this->record->is_locked ? 'Unlock Topic' : 'Lock Topic')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->action(function () {
                    $this->record->update(['is_locked' => ! $this->record->is_locked]);
                    Notification::make()
                        ->title($this->record->is_locked ? 'Topic Locked' : 'Topic Unlocked')
                        ->success()
                        ->send();
                    $this->refreshRecord();
                }),

            DeleteAction::make()
                ->label('Delete Topic & All Replies')
                ->modalDescription('This will permanently delete this topic and all its replies.'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getReplies(): Collection
    {
        return $this->record->replies()->with('user')->orderBy('created_at')->get();
    }

    public function deleteReply(int $replyId): void
    {
        $reply = ForumReply::findOrFail($replyId);
        $reply->delete();

        Notification::make()
            ->title('Reply deleted successfully.')
            ->success()
            ->send();
    }
}
