<?php

namespace Database\Factories;

use App\Models\PrintEdition;
use App\Models\PrintEditionTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrintEditionTranslation>
 */
class PrintEditionTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'print_edition_id' => PrintEdition::factory(),
            'locale' => 'en',
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
