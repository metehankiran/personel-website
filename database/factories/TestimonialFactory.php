<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'title' => fake()->jobTitle(),
            'company' => fake()->company(),
            'body' => fake()->paragraph(),
            'rating' => null,
            'avatar' => null,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function rated(int $stars = 5): static
    {
        return $this->state(fn (array $attributes): array => [
            'rating' => $stars,
        ]);
    }
}
