<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
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
            'value' => fake()->sentence(),
            'type' => 'string',
        ];
    }

    public function boolean(bool $value = true): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => $value ? '1' : '0',
            'type' => 'boolean',
        ]);
    }

    public function json(array $value): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => json_encode($value),
            'type' => 'json',
        ]);
    }
}
