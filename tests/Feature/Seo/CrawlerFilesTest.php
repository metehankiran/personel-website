<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://example.test']);
    URL::forceRootUrl('https://example.test');
});

it('serves an xml sitemap', function () {
    $response = $this->get('/sitemap.xml')->assertOk();

    expect($response->headers->get('Content-Type'))->toStartWith('application/xml');
    expect($response->getContent())->toStartWith('<?xml version="1.0" encoding="UTF-8"?>')
        ->toContain('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

    expect(simplexml_load_string($response->getContent()))->not->toBeFalse();
});

it('lists every public page with absolute Turkish urls', function () {
    $xml = $this->get('/sitemap.xml')->getContent();

    foreach (['', '/hakkimda', '/hizmetler', '/projeler', '/referanslar', '/teknolojiler', '/blog', '/cv', '/iletisim', '/yer-isaretlerim'] as $path) {
        expect($xml)->toContain("<loc>https://example.test{$path}</loc>");
    }
});

it('lists published content with its last modification date', function () {
    $category = Category::factory()->create(['slug' => 'mimari']);
    $tag = Tag::factory()->create(['slug' => 'laravel']);
    $post = Post::factory()->published()->for($category)->hasAttached($tag)->create(['slug' => 'ilk-yazi']);
    Project::factory()->create(['slug' => 'karavela']);
    Page::factory()->published()->create(['slug' => 'kvkk']);

    $xml = $this->get('/sitemap.xml')->getContent();

    expect($xml)->toContain('<loc>https://example.test/blog/ilk-yazi</loc>')
        ->toContain('<lastmod>'.$post->updated_at->toAtomString().'</lastmod>')
        ->toContain('<loc>https://example.test/projeler/karavela</loc>')
        ->toContain('<loc>https://example.test/sayfa/kvkk</loc>')
        ->toContain('<loc>https://example.test/blog/kategori/mimari</loc>')
        ->toContain('<loc>https://example.test/blog/etiket/laravel</loc>');
});

it('leaves drafts, scheduled content and empty taxonomies out of the sitemap', function () {
    Post::factory()->for(Category::factory())->create(['slug' => 'taslak-yazi', 'is_published' => false]);
    Post::factory()->for(Category::factory())->create(['slug' => 'planli-yazi', 'is_published' => true, 'published_at' => now()->addWeek()]);
    Page::factory()->create(['slug' => 'taslak-sayfa']);
    Category::factory()->create(['slug' => 'bos-kategori']);

    $xml = $this->get('/sitemap.xml')->getContent();

    expect($xml)->not->toContain('taslak-yazi')
        ->not->toContain('planli-yazi')
        ->not->toContain('taslak-sayfa')
        ->not->toContain('bos-kategori')
        ->not->toContain('/admin');
});

it('serves robots.txt from the app and points it at the sitemap', function () {
    $response = $this->get('/robots.txt')->assertOk();

    expect($response->headers->get('Content-Type'))->toStartWith('text/plain');
    expect($response->getContent())
        ->toContain('User-agent: *')
        ->toContain('Disallow: /admin')
        ->toContain('Sitemap: https://example.test/sitemap.xml');

    // A static file would shadow the route and its sitemap line.
    expect(file_exists(public_path('robots.txt')))->toBeFalse();
});

it('describes the site for language models in llms.txt', function () {
    $settings = app(GeneralSettings::class);
    $settings->site_title = 'Ada Studio';
    $settings->author_name = 'Ada Yazar';
    $settings->site_description = 'Bağımsız full-stack developer.';
    $settings->save();

    $post = Post::factory()->published()->for(Category::factory())->create(['title' => 'İlk Yazı', 'slug' => 'ilk-yazi', 'excerpt' => 'Kısa özet.']);
    Post::factory()->for(Category::factory())->create(['title' => 'Gizli Taslak', 'is_published' => false]);

    $response = $this->get('/llms.txt')->assertOk();

    expect($response->headers->get('Content-Type'))->toStartWith('text/plain');
    expect($response->getContent())
        ->toStartWith('# Ada Studio')
        ->toContain('> Bağımsız full-stack developer.')
        ->toContain('[Hakkımda](https://example.test/hakkimda)')
        ->toContain('[İlk Yazı](https://example.test/blog/ilk-yazi): Kısa özet.')
        ->not->toContain('Gizli Taslak');
});

it('answers /favicon.ico with the configured favicon instead of an empty file', function () {
    expect(file_exists(public_path('favicon.ico')))->toBeFalse();

    $this->get('/favicon.ico')->assertRedirect('https://example.test/theme/favicon.svg');

    Storage::fake('public');
    Storage::disk('public')->put('settings/fav.png', 'png');

    $settings = app(GeneralSettings::class);
    $settings->favicon_path = 'settings/fav.png';
    $settings->save();

    $this->get('/favicon.ico')->assertRedirect('https://example.test/storage/settings/fav.png');
});
