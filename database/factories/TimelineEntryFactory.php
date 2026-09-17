<?php

namespace Database\Factories;

use App\Models\TimelineEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimelineEntry>
 */
class TimelineEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'period' => (string) fake()->year(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
