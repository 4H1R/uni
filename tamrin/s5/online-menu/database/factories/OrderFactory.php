<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_amount' => fake()->numberBetween(10_000,100_000),
            'delivery_amount' => fake()->numberBetween(10_000,100_000),
            'paying_amount' => fake()->numberBetween(10_000,100_000),
            'description' => fake()->boolean() ? fake()->realText() : null,
        ];
    }
}
