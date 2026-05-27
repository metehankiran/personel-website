<?php

namespace Database\Factories;

use App\Enums\LanguageLevel;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Turkish', 'English', 'German', 'French', 'Spanish']),
            'level' => fake()->randomElement(LanguageLevel::cases()),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
