<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'features' => fake()->randomElements(['API geliştirme', 'Frontend', 'Deployment', 'Code review', 'Refactor'], 3),
            'pricing' => fake()->randomElement(['Starter paket', 'Aylık retainer', 'Saatlik ücret']),
            'duration' => fake()->randomElement(['8 hafta', '4 hafta', null]),
            'badge' => null,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
