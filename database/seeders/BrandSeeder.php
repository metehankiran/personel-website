<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Support\ImageGenerator;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Karavela', 'Flotaki', 'Rezerv', 'Atelye', 'Tezgah', 'Patika',
            'Kasa', 'Kapsül', 'Migros', 'Trendyol', 'Hepsiburada', 'Yapı Kredi',
        ];

        foreach ($brands as $i => $name) {
            Brand::create([
                'name' => $name,
                'logo' => ImageGenerator::logo($name),
                'sort_order' => $i + 1,
            ]);
        }
    }
}
