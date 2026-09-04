<?php

namespace Database\Factories;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => ArticleStatus::Draft,
            'published_at' => null,
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the article is awaiting review.
     */
    public function inReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Review,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the article is published and already visible.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Published,
            'published_at' => now()->subDay(),
        ]);
    }

    /**
     * Indicate that the article is published but scheduled for the future.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Published,
            'published_at' => now()->addWeek(),
        ]);
    }
}
