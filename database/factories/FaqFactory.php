<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => rtrim(fake()->sentence(5), '.').'?',
            'answer' => fake()->paragraph(),
            'is_published' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
