<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

/**
 * Site settings are edited by Administrators only. Keys are fixed by the
 * seeder, so there is no create or delete.
 */
class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->isAdministrator();
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->isAdministrator();
    }
}
