<?php

namespace Database\Factories;

use App\Models\ServiceArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceArea>
 */
class ServiceAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'province' => 'Kütahya',
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'sectors' => fake()->words(3),
            'is_published' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
