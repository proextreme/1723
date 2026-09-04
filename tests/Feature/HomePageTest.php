<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\PrintEdition;
use App\Models\PrintEditionTranslation;
use App\Support\Settings\SettingsRepository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_it_renders_with_the_statement_as_the_h1(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Work gains value through placement')
            ->assertSee('An Independent Fashion &amp; Art Magazine', false);
    }

    public function test_it_shows_published_articles_and_hides_the_rest(): void
    {
        $published = Article::factory()->published()->create();
        ArticleTranslation::factory()->for($published)->create(['locale' => 'en', 'title' => 'A Published Story']);

        $draft = Article::factory()->create();
        ArticleTranslation::factory()->for($draft)->create(['locale' => 'en', 'title' => 'A Hidden Draft']);

        $scheduled = Article::factory()->scheduled()->create();
        ArticleTranslation::factory()->for($scheduled)->create(['locale' => 'en', 'title' => 'A Future Story']);

        $response = $this->get('/');

        $response->assertSee('A Published Story');
        $response->assertDontSee('A Hidden Draft');
        $response->assertDontSee('A Future Story');
    }

    public function test_the_print_section_links_to_the_print_page(): void
    {
        $edition = PrintEdition::factory()->current()->create();
        PrintEditionTranslation::factory()->for($edition)->create(['locale' => 'en', 'title' => 'Issue One']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Print Editions', false)
            ->assertSee(route('print'));
    }

    public function test_home_page_content_is_editable_through_settings(): void
    {
        Storage::fake('public');
        $path = Storage::disk('public')->putFile('home', UploadedFile::fake()->image('feature.jpg', 800, 600));

        $settings = app(SettingsRepository::class);
        $settings->set('home.print_image', $path);
        $settings->set('home.beseen_heading', 'Step Into The Light');

        $this->get('/')
            ->assertOk()
            ->assertSee(Storage::disk('public')->url($path), false)
            ->assertSee('Step Into The Light');
    }

    public function test_the_newsletter_endpoint_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post('/newsletter', ['email' => "a{$i}@example.test"]);
        }

        $this->post('/newsletter', ['email' => 'over@example.test'])->assertStatus(429);
    }

    public function test_placeholder_section_routes_resolve(): void
    {
        foreach (['fashion', 'print', 'submit', 'partnerships', 'faq'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }
}
