<?php

namespace App\Policies;

use App\Models\NewsPost;
use App\Models\User;

class NewsPostPolicy
{
    public function update(User $user, NewsPost $newsPost): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        return $newsPost->user_id === $user->id
            && in_array($newsPost->status, ['draft', 'rejected']);
    }

    public function delete(User $user, NewsPost $newsPost): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        return $newsPost->user_id === $user->id
            && $newsPost->status !== 'published';
    }

    public function submitForReview(User $user, NewsPost $newsPost): bool
    {
        return $newsPost->user_id === $user->id
            && in_array($newsPost->status, ['draft', 'rejected']);
    }

    public function approve(User $user): bool
    {
        return $user->can('approve_news');
    }
}
