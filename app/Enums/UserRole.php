<?php

namespace App\Enums;

enum UserRole: string
{
    case Administrator = 'administrator';
    case ContentAdministrator = 'content_administrator';

    /**
     * Human-readable label for the admin UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::ContentAdministrator => 'Content Administrator',
        };
    }

    /**
     * Every administrator capability is also available to this role.
     */
    public function includes(self $other): bool
    {
        return match ($this) {
            self::Administrator => true,
            self::ContentAdministrator => $other === self::ContentAdministrator,
        };
    }
}
