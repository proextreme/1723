<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleCredit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArticleCredit>
 */
class ArticleCreditFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'article_id' => Article::factory(),
            'label' => fake()->randomElement(['Photographer', 'Stylist', 'Model', 'Makeup', 'Words']),
            'name' => fake()->name(),
            'url' => fake()->optional()->url(),
            'sort_order' => 0,
        ];
    }
}
