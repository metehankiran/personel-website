<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Root User',
            'email' => 'root@personel-website.test',
        ]);

        $this->call([
            SettingsSeeder::class,
            ContactSeeder::class,
            ExperienceSeeder::class,
            SkillSeeder::class,
            LanguageSeeder::class,
            EducationSeeder::class,
            BookmarkSeeder::class,
            PostSeeder::class,
            ProjectSeeder::class,
            TestimonialSeeder::class,
            BrandSeeder::class,
            PageSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
