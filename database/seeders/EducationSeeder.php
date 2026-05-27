<?php

namespace Database\Seeders;

use App\Enums\EducationDegree;
use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        Education::create([
            'school' => 'İstanbul Teknik Üniversitesi',
            'degree' => EducationDegree::Bachelor,
            'field' => 'Bilgisayar Mühendisliği',
            'gpa' => null,
            'start_date' => '2017-09-01',
            'end_date' => '2021-06-01',
            'sort_order' => 1,
        ]);
    }
}
