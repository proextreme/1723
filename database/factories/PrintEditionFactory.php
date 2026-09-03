<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\PrintEdition;
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

    /**
     * Indicate that this is the current print edition.
     *
     * Only one edition may be current; callers are responsible for
     * enforcing that invariant when creating more than one.
     */
    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_current' => true,
        ]);
    }

    /**
     * Create the edition without a cover image.
     */
    public function withoutCover(): static
    {
        return $this->state(fn (array $attributes) => [
            'cover_media_id' => null,
        ]);
    }
}
