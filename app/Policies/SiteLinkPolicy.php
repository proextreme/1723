<?php

namespace App\Policies;

use App\Models\SiteLink;
use App\Models\User;

/**
 * External links and the Media Kit are managed by Administrators only.
 */
class SiteLinkPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function view(User $user, SiteLink $siteLink): bool
    {
        return $user->isAdministrator();
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function update(User $user, SiteLink $siteLink): bool
    {
        return $user->isAdministrator();
    }

    public function delete(User $user, SiteLink $siteLink): bool
    {
        return $user->isAdministrator();
    }
}
