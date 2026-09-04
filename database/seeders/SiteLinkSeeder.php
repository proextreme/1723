<?php

namespace Database\Seeders;

use App\Models\SiteLink;
use Illuminate\Database\Seeder;

/**
 * The fixed set of external links the footer and pages reference by key.
 * URLs are placeholders here and edited in the Admin Panel.
 */
class SiteLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            ['key' => 'instagram', 'label' => 'Instagram', 'url' => 'https://www.instagram.com/', 'target' => '_blank', 'is_active' => true],
            ['key' => 'email', 'label' => 'Email', 'url' => 'mailto:hello@example.com', 'target' => '_self', 'is_active' => true],
            ['key' => 'magcloud', 'label' => 'Print Editions', 'url' => 'https://www.magcloud.com/', 'target' => '_blank', 'is_active' => true],
            ['key' => 'media_kit', 'label' => 'Media Kit', 'url' => null, 'target' => '_blank', 'is_active' => false],
            ['key' => 'submit_editorial', 'label' => 'Editorial Submission', 'url' => 'https://kavyar.com/', 'target' => '_blank', 'is_active' => true],
            ['key' => 'submit_advertorial', 'label' => 'Advertorial Placement', 'url' => 'https://kavyar.com/', 'target' => '_blank', 'is_active' => true],
        ];

        foreach ($links as $link) {
            SiteLink::query()->updateOrCreate(['key' => $link['key']], $link);
        }
    }
}
