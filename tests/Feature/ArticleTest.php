<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\ArticleCredit;
use App\Models\ArticleTranslation;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_published_scope_returns_only_published_articles_with_a_past_publish_time(): void
    {
        $this->freezeTime();

        $visible = Article::factory()->published()->create();
        $draft = Article::factory()->create();
        $inReview = Article::factory()->inReview()->create();
        $scheduled = Article::factory()->scheduled()->create();

        $ids = Article::query()->published()->pluck('id');

        $this->assertTrue($ids->contains($visible->id));
        $this->assertFalse($ids->contains($draft->id));
        $this->assertFalse($ids->contains($inReview->id));
        $this->assertFalse($ids->contains($scheduled->id));
    }

    public function test_status_is_cast_to_the_enum(): void
    {
        $article = Article::factory()->published()->create();

        $this->assertSame(ArticleStatus::Published, $article->fresh()->status);
    }

    public function test_translation_relation_resolves_the_active_locale(): void
    {
        $article = Article::factory()->create();
        ArticleTranslation::factory()->for($article)->create(['locale' => 'en', 'title' => 'Active']);

        $this->assertSame('Active', $article->load('translation')->translation->title);
    }

    public function test_translation_relation_falls_back_when_the_active_locale_is_missing(): void
    {
        config(['app.fallback_locale' => 'en']);
        $this->app->setLocale('fr');

        $article = Article::factory()->create();
        ArticleTranslation::factory()->for($article)->create(['locale' => 'en', 'title' => 'Fallback']);

        $this->assertSame('Fallback', $article->load('translation')->translation->title);
    }

    public function test_credits_come_back_ordered_by_sort_order(): void
    {
        $article = Article::factory()->create();
        ArticleCredit::factory()->for($article)->create(['sort_order' => 2, 'label' => 'Second']);
        ArticleCredit::factory()->for($article)->create(['sort_order' => 1, 'label' => 'First']);

        $this->assertSame(['First', 'Second'], $article->load('credits')->credits->pluck('label')->all());
    }
}
