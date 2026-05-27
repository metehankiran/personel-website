<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-10 years', '-2 years');

        return [
            'school' => fake()->company().' University',
            'degree' => fake()->randomElement(["Bachelor's", "Master's", "Associate's", 'PhD']),
            'field' => fake()->randomElement(['Computer Science', 'Software Engineering', 'Information Systems', 'Mathematics']),
            'description' => fake()->paragraph(),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, 'now'),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function ongoing(): static
    {
        return $this->state([
            'end_date' => null,
            'start_date' => fake()->dateTimeBetween('-4 years', '-1 year'),
        ]);
    }
}
