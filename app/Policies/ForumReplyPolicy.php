<?php

namespace App\Policies;

use App\Models\ForumReply;
use App\Models\User;

class ForumReplyPolicy
{
    public function update(User $user, ForumReply $reply): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']) || $reply->user_id === $user->id;
    }

    public function delete(User $user, ForumReply $reply): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']) || $reply->user_id === $user->id;
    }
}
