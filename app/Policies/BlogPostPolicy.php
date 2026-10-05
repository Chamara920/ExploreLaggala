<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;

class BlogPostPolicy
{
    /**
     * Determine whether the user can update the blog post.
     */
    public function update(User $user, BlogPost $blogPost): bool
    {
        return $blogPost->user_id === $user->id
            && in_array($blogPost->status, ['draft', 'rejected']);
    }

    /**
     * Determine whether the user can delete the blog post.
     */
    public function delete(User $user, BlogPost $blogPost): bool
    {
        return $blogPost->user_id === $user->id
            && $blogPost->status !== 'published';
    }

    /**
     * Determine whether the user can submit the blog post for review.
     */
    public function submitForReview(User $user, BlogPost $blogPost): bool
    {
        return $blogPost->user_id === $user->id
            && in_array($blogPost->status, ['draft', 'rejected']);
    }
}
