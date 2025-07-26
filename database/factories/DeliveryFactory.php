<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mode_of_delivery' => $this->faker->randomElement(['bicycle', 'motorbike', 'car']),
            'base_price' => $this->faker->randomFloat(2, 10, 100),      // e.g. "25.50"
            'price_per_km' => $this->faker->randomFloat(2, 1, 10),       // e.g. "5.75"
            'max_weight' => $this->faker->randomFloat(2, 5, 50),         // e.g. "15.00"
            'max_distance' => $this->faker->randomFloat(2, 5, 500)       // e.g. "150.00"
        ];
    }
}
