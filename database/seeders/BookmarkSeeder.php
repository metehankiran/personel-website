<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    public function run(): void
    {
        $dev = BookmarkCategory::create(['name' => 'Geliştirme', 'description' => 'Backend ve frontend için referans noktalarım.', 'sort_order' => 1]);
        $design = BookmarkCategory::create(['name' => 'Tasarım', 'description' => 'Hızlı estetik kararlar için yardımcı kaynaklar.', 'sort_order' => 2]);
        $reading = BookmarkCategory::create(['name' => 'Okuma', 'description' => 'Yazılım kariyerine değer katan blog ve kitaplar.', 'sort_order' => 3]);

        $bookmarks = [
            ['category_id' => $dev->id, 'url' => 'https://laravel-news.com', 'description' => 'Laravel ekosisteminde olan biten', 'sort_order' => 1],
            ['category_id' => $dev->id, 'url' => 'https://phptherightway.com', 'description' => 'Modern PHP\'ye giriş — geriye dönük temizlik için de', 'sort_order' => 2],
            ['category_id' => $dev->id, 'url' => 'https://caniuse.com', 'description' => 'Tarayıcı uyumluluk tablosu — vazgeçilmez', 'sort_order' => 3],
            ['category_id' => $dev->id, 'url' => 'https://developer.mozilla.org', 'description' => 'Web platformu için tek doğru kaynak', 'sort_order' => 4],
            ['category_id' => $design->id, 'url' => 'https://refactoringui.com', 'description' => 'Pratik UI tavsiyeleri — okuduktan sonra başka türlü düşünüyorsunuz', 'sort_order' => 1],
            ['category_id' => $design->id, 'url' => 'https://www.figma.com', 'description' => 'Tasarım ve prototyping için varsayılan aracım', 'sort_order' => 2],
            ['category_id' => $design->id, 'url' => 'https://coolors.co', 'description' => 'Hızlı renk paleti üreticisi', 'sort_order' => 3],
            ['category_id' => $reading->id, 'url' => 'https://martinfowler.com', 'description' => 'Mimari kararlar üzerine düşünmek için', 'sort_order' => 1],
            ['category_id' => $reading->id, 'url' => 'https://kentcdodds.com', 'description' => 'Test ve frontend hakkında pratik yazılar', 'sort_order' => 2],
            ['category_id' => $reading->id, 'url' => 'https://overreacted.io', 'description' => 'Dan Abramov\'un derinlikli teknik yazıları', 'sort_order' => 3],
        ];

        foreach ($bookmarks as $bookmark) {
            Bookmark::create($bookmark);
        }
    }
}
