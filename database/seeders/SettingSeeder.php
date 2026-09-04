<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * The editable site-configuration contract. Values are intentionally blank —
 * they are set in the Admin Panel. The exact list is still open (brief Q8);
 * adjust as it settles.
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'contact_email', 'type' => 'string'],
            ['key' => 'newsletter_inbox', 'type' => 'string'],
            ['key' => 'footer_note', 'type' => 'string'],
            ['key' => 'seo_default_title', 'type' => 'string'],
            ['key' => 'seo_default_description', 'type' => 'string'],
            ['key' => 'home_print_media_id', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                ['type' => $setting['type']],
            );
        }
    }
}
