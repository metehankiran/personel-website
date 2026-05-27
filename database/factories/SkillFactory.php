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
            'items' => fake()->randomElements(['PHP', 'Node.js', 'Vue.js', 'React', 'Docker', 'Git', 'Laravel', 'Tailwind'], 3),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
