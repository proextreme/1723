<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleWorkflowTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->contentAdministrator()->create());
    }

    public function test_creating_an_article_stores_the_english_translation_and_the_editor(): void
    {
        $editor = auth()->user();

        Livewire::test(CreateArticle::class)
            ->fillForm([
                'status' => ArticleStatus::Draft->value,
                'defaultTranslation.title' => 'The Winter Issue',
                'defaultTranslation.slug' => 'the-winter-issue',
                'defaultTranslation.body_html' => '<p>Body copy.</p>',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = Article::query()->firstOrFail();

        $this->assertSame($editor->id, $article->created_by);
        $this->assertSame(ArticleStatus::Draft, $article->status);
        $this->assertDatabaseHas('article_translations', [
            'article_id' => $article->id,
            'locale' => 'en',
            'title' => 'The Winter Issue',
            'slug' => 'the-winter-issue',
        ]);
    }

    public function test_the_publish_action_sets_the_status_and_stamps_the_publish_time(): void
    {
        $this->freezeTime();

        $article = Article::factory()->inReview()->create();
        ArticleTranslation::factory()->for($article)->create(['locale' => 'en']);

        Livewire::test(EditArticle::class, ['record' => $article->getKey()])
            ->callAction('publish');

        $article->refresh();
        $this->assertSame(ArticleStatus::Published, $article->status);
        $this->assertSame(now()->toDateTimeString(), $article->published_at->toDateTimeString());
    }

    public function test_the_publish_action_is_hidden_once_published(): void
    {
        $article = Article::factory()->published()->create();
        ArticleTranslation::factory()->for($article)->create(['locale' => 'en']);

        Livewire::test(EditArticle::class, ['record' => $article->getKey()])
            ->assertActionHidden('publish')
            ->assertActionVisible('unpublish');
    }
}
