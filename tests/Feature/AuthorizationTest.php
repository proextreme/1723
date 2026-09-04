<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Media;
use App\Models\PrintEdition;
use App\Models\SiteLink;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: bool, 2: bool}>
     */
    public static function abilityMatrix(): array
    {
        return [
            // ability => [both roles?, admin-only?]
            'article.create' => ['article.create', true, true],
            'article.publish' => ['article.publish', true, true],
            'media.create' => ['media.create', true, true],
            'print-edition.create' => ['print-edition.create', true, true],
            'print-edition.setCurrent' => ['print-edition.setCurrent', true, true],
            'site-link.create' => ['site-link.create', false, true],
            'user.create' => ['user.create', false, true],
        ];
    }

    #[DataProvider('abilityMatrix')]
    public function test_content_administrator_permissions(string $ability, bool $contentAdminAllowed, bool $adminAllowed): void
    {
        $user = User::factory()->contentAdministrator()->create();

        $this->assertSame($contentAdminAllowed, $this->allows($user, $ability));
    }

    #[DataProvider('abilityMatrix')]
    public function test_administrator_permissions(string $ability, bool $contentAdminAllowed, bool $adminAllowed): void
    {
        $user = User::factory()->administrator()->create();

        $this->assertSame($adminAllowed, $this->allows($user, $ability));
    }

    public function test_only_an_administrator_may_permanently_delete_an_article(): void
    {
        $article = Article::factory()->create();

        $this->assertFalse(User::factory()->contentAdministrator()->create()->can('forceDelete', $article));
        $this->assertTrue(User::factory()->administrator()->create()->can('forceDelete', $article));
    }

    public function test_an_administrator_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->administrator()->create();
        $other = User::factory()->administrator()->create();

        $this->assertFalse($admin->can('delete', $admin));
        $this->assertTrue($admin->can('delete', $other));
    }

    private function allows(User $user, string $ability): bool
    {
        return match ($ability) {
            'article.create' => $user->can('create', Article::class),
            'article.publish' => $user->can('publish', Article::factory()->make()),
            'media.create' => $user->can('create', Media::class),
            'print-edition.create' => $user->can('create', PrintEdition::class),
            'print-edition.setCurrent' => $user->can('setCurrent', PrintEdition::factory()->make()),
            'site-link.create' => $user->can('create', SiteLink::class),
            'user.create' => $user->can('create', User::class),
        };
    }
}
