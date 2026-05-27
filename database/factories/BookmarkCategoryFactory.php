<?php

namespace Database\Factories;

use App\Models\BookmarkCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookmarkCategory>
 */
class BookmarkCategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Geliştirme', 'Tasarım', 'Okuma', 'Araçlar']),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
