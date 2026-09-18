<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\ImageGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Guides written for local search in Kütahya. Deploys do not seed, so run it once on the server:
 * php artisan db:seed --class=LocalSeoPostSeeder --force
 *
 * Every guide arrives as a draft, and one that already exists is left alone, so running it
 * again never overwrites what was edited in the panel.
 */
class LocalSeoPostSeeder extends Seeder
{
    private const string CATEGORY = 'Kütahya';

    /**
     * slug => [title, excerpt (doubles as the meta description), tags]; the body is posts/{slug}.html.
     *
     * @var array<string, array{0: string, 1: string, 2: array<int, string>}>
     */
    private const array GUIDES = [
        'kutahya-web-sitesi-yaptirmak' => [
            'Kütahya\'da Web Sitesi Yaptırmak: Süreç, Süre ve Dikkat Edilecekler',
            'Kütahya\'da web sitesi yaptırmadan önce bilmeniz gerekenler: doğru kişiyi seçmek, süreç, alan adı ve hosting, teslim sonrası sahiplik.',
            ['Web Tasarım', 'Yerel İşletmeler'],
        ],
        'cini-seramik-e-ticaret-rehberi' => [
            'Çini ve Seramik Atölyeleri İçin E-Ticaret Rehberi',
            'Kütahya çinisini internetten satmak isteyen atölyeler için: pazaryeri mi kendi siteniz mi, fotoğraf, kırılacak ürün kargosu ve yasal adımlar.',
            ['E-Ticaret', 'Yerel İşletmeler'],
        ],
        'kutahya-google-isletme-profili-rehberi' => [
            'Google İşletme Profili Rehberi: Kütahya\'daki İşletmeler Haritada Nasıl Öne Çıkar?',
            'Google Haritalar\'da ve yerel aramalarda görünmek için İşletme Profili nasıl açılır, doğrulanır ve yorumlarla nasıl güçlendirilir? Adım adım.',
            ['Yerel SEO', 'Yerel İşletmeler'],
        ],
        'termal-otel-web-sitesi-rehberi' => [
            'Termal Otel ve Pansiyonlar İçin Web Sitesi: Komisyonsuz Rezervasyon Rehberi',
            'Simav, Gediz ve Emet\'teki termal tesisler için: aracı sitelere bağımlı kalmadan doğrudan rezervasyon almanın yolu iyi bir web sitesinden geçiyor.',
            ['Web Tasarım', 'Turizm'],
        ],
        'web-sitesi-fiyatlari-neye-gore-degisir' => [
            'Web Sitesi Fiyatları Neye Göre Değişir? İşletmeler İçin Maliyet Rehberi',
            'Bir web sitesinin fiyatını belirleyen kalemler, her yıl ödenen giderler ve teklifleri karşılaştırırken sormanız gereken sorular.',
            ['Web Tasarım', 'Yerel İşletmeler'],
        ],
    ];

    public function run(): void
    {
        $category = Category::firstOrCreate(['slug' => Str::slug(self::CATEGORY)], ['name' => self::CATEGORY]);

        foreach (self::GUIDES as $slug => [$title, $excerpt, $tags]) {
            $post = Post::firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => $this->body($slug),
                'cover_image' => ImageGenerator::cover($title),
                'category_id' => $category->id,
                'is_published' => false,
            ]);

            if ($post->wasRecentlyCreated) {
                $post->tags()->sync(collect($tags)->map(fn (string $name): int => Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id));
            }
        }
    }

    /**
     * Links are written as {{route:name}}, {{area:slug}} and {{post:slug}} and resolved through
     * the router, so a changed url never leaves a dead link in a guide.
     */
    private function body(string $slug): string
    {
        $html = File::get(database_path("seeders/posts/{$slug}.html"));

        return trim(preg_replace_callback('/\{\{(route|area|post):([a-z0-9.\-]+)\}\}/', fn (array $match): string => match ($match[1]) {
            'route' => route($match[2], absolute: false),
            'area' => route('service-areas.show', $match[2], absolute: false),
            'post' => array_key_exists($match[2], self::GUIDES)
                ? route('blog.show', $match[2], absolute: false)
                : throw new RuntimeException("Unknown guide [{$match[2]}] linked from [{$slug}]."),
        }, $html));
    }
}
