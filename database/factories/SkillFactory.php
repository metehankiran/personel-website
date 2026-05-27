<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Backend', 'Frontend', 'DevOps', 'Tools']),
            'description' => fake()->sentence(),
            'items' => [
                ['name' => 'PHP', 'description' => fake()->sentence(4), 'level' => fake()->numberBetween(1, 5)],
                ['name' => 'Laravel', 'description' => fake()->sentence(4), 'level' => fake()->numberBetween(1, 5)],
                ['name' => 'Docker', 'description' => fake()->sentence(4), 'level' => fake()->numberBetween(1, 5)],
            ],
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
