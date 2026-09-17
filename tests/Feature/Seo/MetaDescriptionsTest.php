<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Settings\AboutSettings;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $general = app(GeneralSettings::class);
    $general->site_title = 'Ada Studio';
    $general->author_name = 'Ada Yazar';
    $general->bio = 'On yıllık deneyime sahip bağımsız full-stack developer. Laravel ve Vue.js ile ürün geliştiriyorum.';
    $general->save();

    $seo = app(SeoSettings::class);
    $seo->meta_description = 'Ada Yazar — bağımsız full-stack developer. Laravel, Vue.js ile production-hazır ürünler.';
    $seo->save();
});

function metaDescription(string $url): string
{
    preg_match('#<meta name="description" content="(.*?)">#s', test()->get($url)->assertOk()->getContent(), $match);

    return html_entity_decode($match[1] ?? '', ENT_QUOTES);
}

it('gives every top level page its own description', function () {
    Service::factory()->create(['title' => 'Sıfırdan ürün']);
    Skill::factory()->create(['name' => 'Backend']);
    Project::factory()->create(['title' => 'Karavela']);
    Post::factory()->published()->for(Category::factory()->create(['name' => 'Mimari']))->create();

    $descriptions = collect(['home', 'about', 'services', 'projects', 'references', 'stack', 'blog', 'cv', 'contact', 'bookmarks'])
        ->mapWithKeys(fn (string $route): array => [$route => metaDescription(route($route))]);

    expect($descriptions->unique()->count())->toBe($descriptions->count(), 'Duplicate descriptions: '.$descriptions->duplicates()->keys()->implode(', '));

    foreach ($descriptions as $route => $description) {
        expect(mb_strlen($description))->toBeGreaterThanOrEqual(50, "[{$route}] is too short: {$description}")
            ->toBeLessThanOrEqual(160, "[{$route}] is too long: {$description}");
    }
});

it('keeps the site wide description for the home page', function () {
    expect(metaDescription(route('home')))->toBe('Ada Yazar — bağımsız full-stack developer. Laravel, Vue.js ile production-hazır ürünler.');
});

it('builds descriptions from the content managed in the panel', function () {
    Service::factory()->create(['title' => 'Sıfırdan ürün', 'sort_order' => 1]);
    Service::factory()->create(['title' => 'Teknik danışmanlık', 'sort_order' => 2]);
    Skill::factory()->create(['name' => 'Backend', 'sort_order' => 1]);
    Post::factory()->published()->for(Category::factory()->create(['name' => 'Mimari']))->create();

    $about = app(AboutSettings::class);
    $about->body = '<p>Lise yıllarında <em>PHP</em> ile başladım.</p><p>Bugün Laravel ve Vue.js ile ürün geliştiriyorum.</p>';
    $about->save();

    expect(metaDescription(route('services')))->toContain('Sıfırdan ürün')->toContain('Teknik danışmanlık')
        ->and(metaDescription(route('stack')))->toContain('Backend')
        ->and(metaDescription(route('blog')))->toContain('Mimari')
        ->and(metaDescription(route('about')))->toStartWith('Lise yıllarında PHP ile başladım. Bugün Laravel')
        ->and(metaDescription(route('cv')))->toContain('On yıllık deneyime sahip');
});

it('leaves categories without published posts out of the blog description', function () {
    Post::factory()->published()->for(Category::factory()->create(['name' => 'Mimari', 'sort_order' => 2]))->create();
    Category::factory()->create(['name' => 'Boş Kategori', 'sort_order' => 1]);

    expect(metaDescription(route('blog')))->toContain('Mimari')->not->toContain('Boş Kategori');
});

it('describes category and tag listings by their name', function () {
    $category = Category::factory()->create(['name' => 'Mimari']);
    Post::factory()->published()->for($category)->create();

    expect(metaDescription(route('blog.category', $category)))->toContain('Mimari')
        ->not->toBe(metaDescription(route('blog')));
});

it('falls back to the body when a post has no excerpt', function () {
    $post = Post::factory()->published()->for(Category::factory())->create([
        'excerpt' => null,
        'body' => '<h2>Giriş</h2><p>Kuyruk sistemlerinde en sık yapılan hata, işleri idempotent tasarlamamaktır.</p>',
    ]);

    expect(metaDescription(route('blog.show', $post)))->toStartWith('Giriş Kuyruk sistemlerinde en sık yapılan hata');
});

it('describes a static page from its body', function () {
    $page = Page::factory()->published()->create(['body' => '<p>Bu metin kişisel verilerin nasıl işlendiğini açıklar ve KVKK kapsamındaki haklarınızı özetler.</p>']);

    expect(metaDescription(route('pages.show', $page)))->toStartWith('Bu metin kişisel verilerin nasıl işlendiğini açıklar');
});

it('never exceeds 160 characters and cuts on a word boundary', function () {
    $post = Post::factory()->published()->for(Category::factory())->create(['excerpt' => str_repeat('uzun bir cümle parçası ', 20)]);

    $description = metaDescription(route('blog.show', $post));

    expect(mb_strlen($description))->toBeLessThanOrEqual(160)
        ->and($description)->toEndWith('…')
        ->and($description)->not->toMatch('/parç…$/u');
});

it('skips the "last updated" line when describing a legal page', function () {
    $page = Page::factory()->published()->create([
        'body' => '<p>Son güncelleme: 17 Eylül 2026</p><p>Bu politika, sitede hangi çerezlerin hangi amaçla kullanıldığını açıklar.</p>',
    ]);

    expect(metaDescription(route('pages.show', $page)))->toStartWith('Bu politika, sitede hangi çerezlerin');
});
