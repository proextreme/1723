<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PrintEdition;
use App\Models\User;

/**
 * Both roles manage print editions. Permanent deletion is reserved for
 * Administrators.
 */
class PrintEditionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function view(User $user, PrintEdition $printEdition): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function update(User $user, PrintEdition $printEdition): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function setCurrent(User $user, PrintEdition $printEdition): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function delete(User $user, PrintEdition $printEdition): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function restore(User $user, PrintEdition $printEdition): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function forceDelete(User $user, PrintEdition $printEdition): bool
    {
        return $user->isAdministrator();
    }
}
