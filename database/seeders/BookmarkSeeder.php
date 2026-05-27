<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    public function run(): void
    {
        BookmarkCategory::factory()
            ->count(3)
            ->has(Bookmark::factory()->count(4))
            ->create();
    }
}
