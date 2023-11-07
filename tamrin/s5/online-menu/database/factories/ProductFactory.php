<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->numberBetween(5_000, 500_000);

        return [
            'name' => fake()->words(fake()->numberBetween(3, 7), true),
            'price' => $price,
            'previous_price' => fake()->boolean() ? fake()->numberBetween($price, $price + fake()->numberBetween(1_000, 500_000)) : null,
            'short_description' => fake()->boolean(80) ? fake()->text((fake()->numberBetween(40, 60))) : null,
            'description' => fake()->boolean() ? fake()->paragraphs(fake()->numberBetween(1, 5), true) : null,
            'quantity' => fake()->numberBetween(0, 99),
            'is_active' => fake()->boolean(),
        ];
    }
}
