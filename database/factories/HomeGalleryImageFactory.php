<?php

namespace Database\Factories;

use App\Models\HomeGalleryImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomeGalleryImage>
 */
class HomeGalleryImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'disk' => 'public',
            'path' => 'home/gallery/'.fake()->unique()->uuid().'.jpg',
            'alt' => fake()->sentence(6),
            'url' => fake()->optional()->url(),
            'sort_order' => 0,
        ];
    }

    public function withLink(): static
    {
        return $this->state(fn (array $attributes) => [
            'url' => fake()->url(),
        ]);
    }
}
