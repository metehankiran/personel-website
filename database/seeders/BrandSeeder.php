<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Karavela', 'sort_order' => 1],
            ['name' => 'Flotaki', 'sort_order' => 2],
            ['name' => 'Rezerv', 'sort_order' => 3],
            ['name' => 'Atelye', 'sort_order' => 4],
            ['name' => 'Tezgah', 'sort_order' => 5],
            ['name' => 'Patika', 'sort_order' => 6],
            ['name' => 'Kasa', 'sort_order' => 7],
            ['name' => 'Kapsül', 'sort_order' => 8],
            ['name' => 'Migros', 'sort_order' => 9],
            ['name' => 'Trendyol', 'sort_order' => 10],
            ['name' => 'Hepsiburada', 'sort_order' => 11],
            ['name' => 'Yapı Kredi', 'sort_order' => 12],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
