<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\Media;
use App\Models\PrintEdition;
use App\Models\PrintEditionTranslation;
use App\Models\SiteLink;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminPanelPagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function listAndCreatePages(): array
    {
        return [
            'articles index' => ['/admin/articles'],
            'articles create' => ['/admin/articles/create'],
            'media index' => ['/admin/media'],
            'media create' => ['/admin/media/create'],
            'print editions index' => ['/admin/print-editions'],
            'print editions create' => ['/admin/print-editions/create'],
            'site links index' => ['/admin/site-links'],
            'users index' => ['/admin/users'],
            'users create' => ['/admin/users/create'],
            'settings' => ['/admin/manage-settings'],
            'media kit' => ['/admin/manage-media-kit'],
        ];
    }

    #[DataProvider('listAndCreatePages')]
    public function test_administrator_can_open_admin_pages(string $path): void
    {
        $this->actingAs(User::factory()->administrator()->create())
            ->get($path)
            ->assertOk();
    }

    public function test_edit_pages_render(): void
    {
        $admin = User::factory()->administrator()->create();
        $this->actingAs($admin);

        $article = Article::factory()->has(ArticleTranslation::factory()->state(['locale' => 'en']), 'translations')->create();
        $edition = PrintEdition::factory()->create();
        PrintEditionTranslation::factory()->for($edition)->create(['locale' => 'en']);
        $media = Media::factory()->create();
        $link = SiteLink::factory()->create();

        $this->get("/admin/articles/{$article->id}/edit")->assertOk();
        $this->get("/admin/print-editions/{$edition->id}/edit")->assertOk();
        $this->get("/admin/media/{$media->id}/edit")->assertOk();
        $this->get("/admin/site-links/{$link->id}/edit")->assertOk();
    }

    public function test_a_content_administrator_cannot_open_the_users_resource(): void
    {
        $this->actingAs(User::factory()->contentAdministrator()->create())
            ->get('/admin/users')
            ->assertForbidden();
    }

    public function test_a_content_administrator_cannot_open_print_editions(): void
    {
        $this->actingAs(User::factory()->contentAdministrator()->create())
            ->get('/admin/print-editions')
            ->assertForbidden();
    }
}
