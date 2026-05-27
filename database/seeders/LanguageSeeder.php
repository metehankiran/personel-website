<?php

namespace Database\Seeders;

use App\Enums\LanguageLevel;
use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        Language::create(['name' => 'Türkçe', 'level' => LanguageLevel::Native, 'sort_order' => 1]);
        Language::create(['name' => 'İngilizce', 'level' => LanguageLevel::Advanced, 'sort_order' => 2]);
    }
}
