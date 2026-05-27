<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Laravel', '.NET', 'Vue', 'Mimari', 'Freelance', 'DevOps', 'Postgres', 'TypeScript']);

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'parent_id' => null,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function child(Category $parent): static
    {
        return $this->state(['parent_id' => $parent->id]);
    }
}
