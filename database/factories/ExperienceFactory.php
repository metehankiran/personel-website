<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-10 years', '-1 year');

        return [
            'title' => fake()->jobTitle(),
            'company' => fake()->company(),
            'description' => fake()->paragraph(),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, 'now'),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function current(): static
    {
        return $this->state([
            'end_date' => null,
            'start_date' => fake()->dateTimeBetween('-3 years', '-1 month'),
        ]);
    }
}
