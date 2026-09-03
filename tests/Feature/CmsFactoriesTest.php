<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCredit;
use App\Models\ArticleTranslation;
use App\Models\Media;
use App\Models\PrintEdition;
use App\Models\PrintEditionTranslation;
use App\Models\SiteLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsFactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_factory_creates_a_draft_with_creator(): void
    {
        $article = Article::factory()->create();

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'status' => 'draft']);
        $this->assertNull($article->published_at);
        $this->assertInstanceOf(User::class, $article->creator);
    }

    public function test_article_published_state_sets_status_and_timestamp(): void
    {
        $article = Article::factory()->published()->create();

        $this->assertSame('published', $article->status);
        $this->assertNotNull($article->published_at);
        $this->assertTrue($article->published_at->isPast());
    }

    public function test_article_translation_factory_links_to_article(): void
    {
        $translation = ArticleTranslation::factory()->create(['locale' => 'en']);

        $this->assertInstanceOf(Article::class, $translation->article);
        $this->assertDatabaseHas('article_translations', [
            'id' => $translation->id,
            'locale' => 'en',
        ]);
    }

    public function test_article_credits_are_ordered_by_sort_order(): void
    {
        $article = Article::factory()->create();
        ArticleCredit::factory()->for($article)->create(['sort_order' => 2, 'label' => 'Second']);
        ArticleCredit::factory()->for($article)->create(['sort_order' => 1, 'label' => 'First']);

        $this->assertSame(['First', 'Second'], $article->credits->pluck('label')->all());
    }

    public function test_media_factory_persists_dimensions_and_creator(): void
    {
        $media = Media::factory()->create();

        $this->assertInstanceOf(User::class, $media->creator);
        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
        ]);
    }

    public function test_article_media_pivot_carries_ordering_and_feature_flag(): void
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

    public function test_print_edition_current_state(): void
    {
        $edition = PrintEdition::factory()->current()->create();

        $this->assertTrue($edition->is_current);
        $this->assertInstanceOf(Media::class, $edition->coverMedia);
    }

    public function test_print_edition_translation_factory_links_to_edition(): void
    {
        $translation = PrintEditionTranslation::factory()->create();

        $this->assertInstanceOf(PrintEdition::class, $translation->printEdition);
    }

    public function test_site_link_factory_creates_active_link(): void
    {
        $link = SiteLink::factory()->create();

        $this->assertTrue($link->is_active);
        $this->assertDatabaseHas('site_links', ['id' => $link->id, 'key' => $link->key]);
    }
}
