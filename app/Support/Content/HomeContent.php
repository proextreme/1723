<?php

namespace App\Support\Content;

use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Facades\Storage;

/**
 * Editable content for the public home page. Every value has a default (the
 * copy shipped in the Figma design); the Admin Panel's "Home page" screen
 * overrides them through settings prefixed `home.`.
 */
class HomeContent
{
    /**
     * key => default copy. Image keys hold a stored path, not text.
     *
     * @var array<string, string>
     */
    public const DEFAULTS = [
        'hero_tagline' => 'Place of Power for Creators All Over the Globe',

        'statement_heading' => 'Work gains value through placement',
        'statement_body' => 'Publishing fashion editorials, cover stories, photography, art and creative projects from photographers, stylists, designers and artists worldwide.',

        'covers_heading' => 'Front Covers',
        'covers_body' => 'The highest level of placement at 17:23 MAG. Explore front cover opportunities through advertorial collaborations and selected partnerships.',

        'print_heading' => 'Print Editions',
        'print_quote' => 'In a fast digital world, print becomes a form of quiet luxury',
        'print_body' => "Limited print editions of 17:23 MAG, bringing together fashion editorials, front covers, interviews, advertorials and creative photography from the world's leading and emerging creative talent.",

        'beseen_heading' => 'Enter 17:23 Be Seen',

        'newsletter_heading' => "What's In It For Me?",
        'newsletter_body' => 'The environment around the work defines its value. Where it appears, who it stands next to, and how it is presented define its impact. At 17:23, context becomes outcome.',
        'newsletter_tagline' => 'The right place. The right time. The right eyes on your work.',
    ];

    public const IMAGE_KEYS = ['hero_image', 'print_image', 'beseen_image'];

    public function __construct(private readonly SettingsRepository $settings) {}

    public function text(string $key): string
    {
        $value = $this->settings->get('home.'.$key);

        return is_string($value) && $value !== '' ? $value : (self::DEFAULTS[$key] ?? '');
    }

    /**
     * A public URL for a section's background image, or null to fall back to
     * the built-in asset / placeholder.
     */
    public function image(string $key): ?string
    {
        $path = $this->settings->get('home.'.$key);

        return is_string($path) && $path !== '' && Storage::disk('public')->exists($path)
            ? Storage::disk('public')->url($path)
            : null;
    }
}
