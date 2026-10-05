<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function update(User $user, Event $event): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        return $event->user_id === $user->id
            && in_array($event->status, ['draft', 'rejected']);
    }

    public function delete(User $user, Event $event): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        return $event->user_id === $user->id
            && $event->status !== 'published';
    }

    public function submitForReview(User $user, Event $event): bool
    {
        return $event->user_id === $user->id
            && in_array($event->status, ['draft', 'rejected']);
    }

    public function approve(User $user): bool
    {
        return $user->can('approve_events');
    }
}
