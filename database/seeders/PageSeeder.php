<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'title' => 'KVKK Aydınlatma Metni',
            'slug' => 'kvkk',
            'body' => 'KVKK aydınlatma metni içeriği buraya gelecek.',
            'is_published' => true,
        ]);

        Page::create([
            'title' => 'Çerez Politikası',
            'slug' => 'cookie-policy',
            'body' => 'Çerez politikası içeriği buraya gelecek.',
            'is_published' => true,
        ]);
    }
}
