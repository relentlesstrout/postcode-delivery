<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'latitude' => fake()->latitude(49,59),
            'longitude' => fake()->longitude(-8,2),
            'is_open' => fake()->boolean(),
            'type' => fake()->randomElement(['takeaway', 'shop', 'restaurant']),
            'max_delivery_distance' => fake()->randomFloat(2, 7, 10)
        ];
    }
}
