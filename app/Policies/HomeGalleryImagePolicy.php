<?php

namespace App\Policies;

use App\Models\HomeGalleryImage;
use App\Models\User;

/**
 * The home page image grid is managed by Administrators, alongside the rest of
 * the home page content.
 */
class HomeGalleryImagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function view(User $user, HomeGalleryImage $image): bool
    {
        return $user->isAdministrator();
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function update(User $user, HomeGalleryImage $image): bool
    {
        return $user->isAdministrator();
    }

    public function delete(User $user, HomeGalleryImage $image): bool
    {
        return $user->isAdministrator();
    }
}
