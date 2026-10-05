<?php

namespace App\Policies;

use App\Models\CommunityOrganization;
use App\Models\User;

class CommunityOrganizationPolicy
{
    public function update(User $user, CommunityOrganization $org): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        return $org->user_id === $user->id
            && in_array($org->status, ['draft', 'rejected']);
    }

    public function delete(User $user, CommunityOrganization $org): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        return $org->user_id === $user->id
            && $org->status !== 'published';
    }

    public function submitForReview(User $user, CommunityOrganization $org): bool
    {
        return $org->user_id === $user->id
            && in_array($org->status, ['draft', 'rejected']);
    }

    public function approve(User $user): bool
    {
        return $user->can('approve_organizations');
    }
}
