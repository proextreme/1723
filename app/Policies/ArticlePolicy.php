<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Article;
use App\Models\User;

/**
 * Articles are a shared editorial workspace: any Content Administrator may
 * manage and publish any article. Authorship is recorded, not enforced.
 * Permanent deletion is reserved for Administrators.
 */
class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function view(User $user, Article $article): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function publish(User $user, Article $article): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function restore(User $user, Article $article): bool
    {
        return $user->hasRole(UserRole::ContentAdministrator);
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return $user->isAdministrator();
    }
}
