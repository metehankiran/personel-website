<?php

namespace Database\Factories;

use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bookmark>
 */
class BookmarkFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => BookmarkCategory::factory(),
            'url' => fake()->url(),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
