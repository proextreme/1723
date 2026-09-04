<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Support\Settings\SettingsRepository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SettingsRepositoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function repository(): SettingsRepository
    {
        return app(SettingsRepository::class);
    }

    public function test_returns_the_default_when_the_key_is_absent(): void
    {
        $this->assertSame('fallback', $this->repository()->get('missing', 'fallback'));
    }

    public function test_casts_values_by_type(): void
    {
        Setting::query()->create(['key' => 'contact_email', 'value' => 'hi@1723.test', 'type' => 'string']);
        Setting::query()->create(['key' => 'newsletter_enabled', 'value' => '1', 'type' => 'boolean']);
        Setting::query()->create(['key' => 'max_items', 'value' => '12', 'type' => 'integer']);
        Setting::query()->create(['key' => 'nav', 'value' => '{"a":1}', 'type' => 'json']);

        $repository = $this->repository();

        $this->assertSame('hi@1723.test', $repository->get('contact_email'));
        $this->assertTrue($repository->get('newsletter_enabled'));
        $this->assertSame(12, $repository->get('max_items'));
        $this->assertSame(['a' => 1], $repository->get('nav'));
    }

    public function test_set_writes_and_is_immediately_readable(): void
    {
        $repository = $this->repository();

        $repository->get('footer_note'); // warm the cache with an empty table
        $repository->set('footer_note', 'Made in print', 'string');

        $this->assertSame('Made in print', $repository->get('footer_note'));
        $this->assertDatabaseHas('settings', ['key' => 'footer_note', 'value' => 'Made in print']);
    }
}
