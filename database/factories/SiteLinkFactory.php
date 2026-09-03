<?php

namespace Database\Factories;

use App\Models\SiteLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteLink>
 */
class SiteLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2),
            'label' => fake()->words(2, true),
            'url' => fake()->url(),
            'target' => '_blank',
            'media_id' => null,
            'is_active' => true,
        ];
    }
}
