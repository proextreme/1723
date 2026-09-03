<?php

namespace Database\Factories;

use App\Models\PrintEdition;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrintEdition>
 */
class PrintEditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'issue_number' => fake()->unique()->numberBetween(1, 99),
            'release_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'magcloud_url' => 'https://www.magcloud.com/',
            'cover_media_id' => Media::factory(),
            'is_current' => false,
            'sort_order' => 0,
        ];
    }
}
