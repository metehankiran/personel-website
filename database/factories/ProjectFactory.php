<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->word(),
            'slug' => fake()->unique()->slug(),
            'category_id' => ProjectCategory::factory(),
            'description' => fake()->paragraph(),
            'body' => fake()->paragraphs(5, true),
            'cover_image' => null,
            'client' => fake()->company(),
            'year' => fake()->numberBetween(2020, 2025),
            'duration' => fake()->randomElement(['3 ay', '5 ay', '8 ay', '12 ay']),
            'role' => fake()->randomElement(['Lead developer', 'Full-stack developer', 'Backend developer']),
            'extras' => null,
            'stack' => fake()->randomElements(['Laravel', 'Vue 3', 'PostgreSQL', '.NET Core', 'React', 'Redis', 'MySQL'], 3),
            'stats' => null,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
