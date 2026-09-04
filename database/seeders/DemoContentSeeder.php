<?php

namespace Database\Seeders;

use App\Actions\SetCurrentPrintEdition;
use App\Models\Article;
use App\Models\ArticleCredit;
use App\Models\ArticleTranslation;
use App\Models\Media;
use App\Models\PrintEdition;
use App\Models\PrintEditionTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Sample editorial and print content for local development and the staging
 * preview. Skips itself once any article exists so it is safe to re-run.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Article::query()->withTrashed()->exists()) {
            return;
        }

        $author = User::query()->where('role', 'content_administrator')->first()
            ?? User::factory()->contentAdministrator()->create();

        $this->seedArticles($author);
        $this->seedPrintEditions($author);
    }

    private function seedArticles(User $author): void
    {
        $states = [
            ['factory' => fn () => Article::factory()->published(), 'count' => 4],
            ['factory' => fn () => Article::factory()->inReview(), 'count' => 1],
            ['factory' => fn () => Article::factory(), 'count' => 2],
        ];

        foreach ($states as $state) {
            for ($i = 0; $i < $state['count']; $i++) {
                $article = ($state['factory'])()->create([
                    'created_by' => $author->id,
                    'updated_by' => $author->id,
                ]);

                ArticleTranslation::factory()->for($article)->create();

                $media = Media::factory()
                    ->count(fake()->numberBetween(2, 5))
                    ->create(['created_by' => $author->id]);

                $article->media()->attach(
                    $media->mapWithKeys(fn (Media $item, int $index): array => [
                        $item->id => [
                            'sort_order' => $index,
                            'is_featured' => $index === 0,
                            'caption' => fake()->optional()->sentence(),
                        ],
                    ])->all(),
                );

                ArticleCredit::factory()
                    ->count(3)
                    ->for($article)
                    ->sequence(
                        ['label' => 'Photographer', 'sort_order' => 0],
                        ['label' => 'Stylist', 'sort_order' => 1],
                        ['label' => 'Words', 'sort_order' => 2],
                    )
                    ->create();
            }
        }
    }

    private function seedPrintEditions(User $author): void
    {
        $editions = collect(range(1, 3))->map(function (int $issue) use ($author): PrintEdition {
            $edition = PrintEdition::factory()->create([
                'issue_number' => $issue,
                'sort_order' => $issue - 1,
                'cover_media_id' => Media::factory()->create(['created_by' => $author->id]),
            ]);

            PrintEditionTranslation::factory()->for($edition)->create();

            return $edition;
        });

        app(SetCurrentPrintEdition::class)->handle($editions->last());
    }
}
