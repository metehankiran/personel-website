<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'Docker', 'CI/CD', 'PostgreSQL', 'Backend', 'Frontend', 'Architecture']);

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
