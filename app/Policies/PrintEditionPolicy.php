<?php

namespace App\Policies;

use App\Models\PrintEdition;
use App\Models\User;

/**
 * Print editions are managed by Administrators only.
 */
class PrintEditionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function view(User $user, PrintEdition $printEdition): bool
    {
        return $user->isAdministrator();
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function update(User $user, PrintEdition $printEdition): bool
    {
        return $user->isAdministrator();
    }

    public function setCurrent(User $user, PrintEdition $printEdition): bool
    {
        return $user->isAdministrator();
    }

    public function delete(User $user, PrintEdition $printEdition): bool
    {
        return $user->isAdministrator();
    }

    public function restore(User $user, PrintEdition $printEdition): bool
    {
        return $user->isAdministrator();
    }

    public function forceDelete(User $user, PrintEdition $printEdition): bool
    {
        return $user->isAdministrator();
    }
}
