<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Media;
use App\Models\User;

/**
 * Media is managed alongside articles, so both roles may upload and edit it.
 * Permanent deletion is reserved for Administrators.
 */
class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function view(User $user, Media $media): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function update(User $user, Media $media): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function restore(User $user, Media $media): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return $user->isAdministrator();
    }
}
