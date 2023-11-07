<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(fake()->numberBetween(1, 6), true),
            'description' => fake()->paragraphs(fake()->numberBetween(1, 8), true),
            'email' => fake()->email(),
            'replied_at' => fake()->dateTime(),
        ];
    }
}
