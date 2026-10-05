<?php

namespace App\Policies;

use App\Models\ForumTopic;
use App\Models\User;

class ForumTopicPolicy
{
    public function update(User $user, ForumTopic $topic): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']) || $topic->user_id === $user->id;
    }

    public function delete(User $user, ForumTopic $topic): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']) || $topic->user_id === $user->id;
    }

    public function moderate(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']) || $user->can('moderate_forum');
    }
}
