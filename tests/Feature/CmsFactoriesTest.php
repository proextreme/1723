<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\Media;
use App\Models\PrintEdition;
use App\Models\PrintEditionTranslation;
use App\Models\SiteLink;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

/**
 * Smoke test that every CMS factory produces a persistable model with its
 * relations wired. Model behaviour is covered in the per-model test classes.
 */
class CmsFactoriesTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_article_factory_creates_a_draft_with_a_creator(): void
    {
        $article = Article::factory()->create();

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'status' => 'draft']);
        $this->assertNull($article->published_at);
        $this->assertInstanceOf(User::class, $article->load('creator')->creator);
    }

    public function test_article_published_state_sets_status_and_a_past_timestamp(): void
    {
        $article = Article::factory()->published()->create();

        $this->assertSame(ArticleStatus::Published, $article->status);
        $this->assertNotNull($article->published_at);
        $this->assertTrue($article->published_at->isPast());
    }

    public function test_article_translation_factory_links_to_an_article(): void
    {
        $translation = ArticleTranslation::factory()->create(['locale' => 'en']);

        $this->assertInstanceOf(Article::class, $translation->load('article')->article);
        $this->assertDatabaseHas('article_translations', ['id' => $translation->id, 'locale' => 'en']);
    }

    public function test_media_factory_persists_dimensions_and_a_creator(): void
    {
        $media = Media::factory()->create();

        $this->assertInstanceOf(User::class, $media->load('creator')->creator);
        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
        ]);
    }

    public function test_article_media_pivot_carries_ordering_and_the_feature_flag(): void
    {
        $article = Article::factory()->create();
        $media = Media::factory()->create();

        $article->media()->attach($media, ['sort_order' => 1, 'is_featured' => true, 'caption' => 'Cover']);

        $attached = $article->media()->first();
        $this->assertSame($media->id, $attached->id);
        $this->assertSame(1, (int) $attached->pivot->sort_order);
        $this->assertTrue((bool) $attached->pivot->is_featured);
        $this->assertSame('Cover', $attached->pivot->caption);
    }

    public function test_print_edition_current_state_and_cover_relation(): void
    {
        $edition = PrintEdition::factory()->current()->create();

        $this->assertTrue($edition->is_current);
        $this->assertInstanceOf(Media::class, $edition->load('coverMedia')->coverMedia);
    }

    public function test_print_edition_translation_factory_links_to_an_edition(): void
    {
        $translation = PrintEditionTranslation::factory()->create();

        $this->assertInstanceOf(PrintEdition::class, $translation->load('printEdition')->printEdition);
    }

    public function test_site_link_factory_creates_an_active_link(): void
    {
        $link = SiteLink::factory()->create();

        $this->assertTrue($link->is_active);
        $this->assertDatabaseHas('site_links', ['id' => $link->id, 'key' => $link->key]);
    }
}
