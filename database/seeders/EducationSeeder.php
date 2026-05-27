<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        Education::factory()->count(2)->create();
        Education::factory()->ongoing()->create(['sort_order' => 0]);
    }
}
