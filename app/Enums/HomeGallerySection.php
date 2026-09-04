<?php

namespace App\Enums;

enum HomeGallerySection: string
{
    case Statement = 'statement';
    case Covers = 'covers';

    /**
     * Human-readable label for the admin UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Statement => 'Editorial grid',
            self::Covers => 'Front covers',
        };
    }
}
