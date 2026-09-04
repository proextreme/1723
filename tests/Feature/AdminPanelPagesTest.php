<?php

namespace Tests\Feature;

use App\Filament\Resources\HomeGalleryImages\Pages\ManageHomeGalleryImages;
use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\HomeGalleryImage;
use App\Models\Media;
use App\Models\PrintEdition;
use App\Models\PrintEditionTranslation;
use App\Models\SiteLink;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
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
            'home page' => ['/admin/manage-home-page'],
            'home gallery' => ['/admin/home-gallery-images'],
        ];
    }

    #[DataProvider('listAndCreatePages')]
    public function test_administrator_can_open_admin_pages(string $path): void
    {
        // Every list table needs at least one row so column closures actually run.
        $article = Article::factory()->published()->create();
        ArticleTranslation::factory()->for($article)->create(['locale' => 'en']);
        $edition = PrintEdition::factory()->current()->create();
        PrintEditionTranslation::factory()->for($edition)->create(['locale' => 'en']);
        Media::factory()->create();
        SiteLink::factory()->create(['target' => '_blank']);
        SiteLink::factory()->create(['target' => '_self']);
        HomeGalleryImage::factory()->create(['url' => 'https://example.test']);
        HomeGalleryImage::factory()->covers()->create(['url' => null]);
        User::factory()->contentAdministrator()->create();

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

    public function test_a_content_administrator_can_open_print_editions(): void
    {
        $this->actingAs(User::factory()->contentAdministrator()->create())
            ->get('/admin/print-editions')
            ->assertOk();
    }

    public function test_home_images_tabs_filter_by_section(): void
    {
        HomeGalleryImage::factory()->create(['alt' => 'An editorial grid image']);
        HomeGalleryImage::factory()->covers()->create(['alt' => 'A front covers image']);

        Livewire::actingAs(User::factory()->administrator()->create())
            ->test(ManageHomeGalleryImages::class)
            ->assertCanSeeTableRecords(HomeGalleryImage::all())
            ->set('activeTab', 'covers')
            ->assertCountTableRecords(1);
    }
}
