<?php

namespace App\Policies;

use App\Models\User;

/**
 * User and role management is for Administrators only. A user cannot delete
 * their own account.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdministrator();
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isAdministrator();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isAdministrator() && $user->isNot($model);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->isAdministrator();
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdministrator() && $user->isNot($model);
    }
}
