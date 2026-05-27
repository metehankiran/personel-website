<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->sentence(),
            'message' => fake()->paragraphs(3, true),
            'is_read' => false,
        ];
    }

    public function read(): static
    {
        return $this->state(['is_read' => true]);
    }
}
