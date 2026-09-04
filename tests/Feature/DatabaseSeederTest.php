<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\PrintEdition;
use App\Models\SiteLink;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeding_creates_both_admin_roles_the_site_links_and_demo_content(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['email' => 'admin@1723.test', 'role' => 'administrator']);
        $this->assertDatabaseHas('users', ['email' => 'editor@1723.test', 'role' => 'content_administrator']);

        $this->assertTrue(SiteLink::query()->whereIn('key', ['instagram', 'email', 'magcloud', 'media_kit'])->count() === 4);

        $this->assertGreaterThan(0, Article::query()->published()->count());
        $this->assertSame(1, PrintEdition::query()->where('is_current', true)->count());
    }

    public function test_seeding_is_safe_to_run_twice(): void
    {
        $this->seed();
        $articleCount = Article::query()->count();

        $this->seed();

        $this->assertSame($articleCount, Article::query()->count());
        $this->assertSame(2, User::query()->count());
    }
}
