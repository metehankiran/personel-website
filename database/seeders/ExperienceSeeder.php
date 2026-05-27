<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            [
                'title' => 'Bağımsız Full-stack Developer',
                'company' => 'Freelance',
                'description' => '40+ müşteriyle e-ticaret, CRM, SaaS ve dahili araç projeleri. Laravel, Vue 3, .NET Core, Postgres.',
                'start_date' => '2022-01-01',
                'end_date' => null,
                'sort_order' => 1,
            ],
            [
                'title' => 'Senior Backend Developer',
                'company' => 'Trio Software',
                'description' => 'B2B SaaS ürünleri. PHP/Laravel ekibi. Mimari kararlar, code review, mentorship.',
                'start_date' => '2020-01-01',
                'end_date' => '2022-01-01',
                'sort_order' => 2,
            ],
            [
                'title' => 'Junior Developer',
                'company' => 'Atak Yazılım',
                'description' => 'WordPress, vanilla PHP, MySQL. İlk freelance işlerimi alarak başladığım dönem.',
                'start_date' => '2019-01-01',
                'end_date' => '2020-01-01',
                'sort_order' => 3,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::create($experience);
        }
    }
}
