<?php

namespace Database\Factories;

use App\Models\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectCategory>
 */
class ProjectCategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['SaaS', 'CRM', 'API', 'Web']);

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
