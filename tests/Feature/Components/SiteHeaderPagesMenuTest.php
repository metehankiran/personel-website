<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * The header markup only, so page bodies and the footer cannot satisfy an assertion by accident.
 */
function headerHtml(string $url): string
{
    $html = test()->get($url)->assertOk()->getContent();

    return Str::between($html, '<header', '</header>');
}

it('lists the published static pages under a "Sayfalar" menu on desktop and mobile', function () {
    Page::factory()->published()->create(['title' => 'KVKK Aydınlatma Metni', 'slug' => 'kvkk']);
    Page::factory()->published()->create(['title' => 'Çerez Politikası', 'slug' => 'cookie-policy']);

    $header = headerHtml(route('home'));

    expect(substr_count($header, 'data-nav-group="pages"'))->toBe(2)
        ->and($header)->toContain('Sayfalar');

    foreach (['kvkk' => 'KVKK Aydınlatma Metni', 'cookie-policy' => 'Çerez Politikası'] as $slug => $title) {
        expect(substr_count($header, 'href="'.route('pages.show', $slug).'"'))->toBe(2, "{$slug} should be linked once per menu")
            ->and($header)->toContain($title);
    }
});

it('leaves drafts and scheduled pages out of the menu', function () {
    Page::factory()->published()->create(['title' => 'Yayındaki Sayfa', 'slug' => 'yayinda']);
    Page::factory()->create(['title' => 'Taslak Sayfa', 'slug' => 'taslak']);
    Page::factory()->create(['title' => 'Planlı Sayfa', 'slug' => 'planli', 'is_published' => true, 'published_at' => now()->addWeek()]);

    expect(headerHtml(route('home')))->toContain('Yayındaki Sayfa')
        ->not->toContain('Taslak Sayfa')
        ->not->toContain('Planlı Sayfa');
});

it('still shows the menu with the faq link when no static page is published', function () {
    Page::factory()->create(['title' => 'Taslak Sayfa']);

    $header = headerHtml(route('home'));

    expect(substr_count($header, 'data-nav-group="pages"'))->toBe(2)
        ->and($header)->toContain('Sıkça Sorulan Sorular')
        ->not->toContain('Taslak Sayfa');
});

it('orders the pages by title with Turkish letters in their place', function () {
    Page::factory()->published()->create(['title' => 'KVKK Aydınlatma Metni']);
    Page::factory()->published()->create(['title' => 'Çerez Politikası']);
    Page::factory()->published()->create(['title' => 'Şartlar']);

    $header = headerHtml(route('home'));

    expect(strpos($header, 'Çerez Politikası'))->toBeLessThan(strpos($header, 'KVKK Aydınlatma Metni'))
        ->and(strpos($header, 'KVKK Aydınlatma Metni'))->toBeLessThan(strpos($header, 'Şartlar'));
});

it('marks the menu and the open page as current on a static page', function () {
    $open = Page::factory()->published()->create(['title' => 'KVKK Aydınlatma Metni', 'slug' => 'kvkk']);
    Page::factory()->published()->create(['title' => 'Çerez Politikası', 'slug' => 'cookie-policy']);

    $header = headerHtml(route('pages.show', $open));

    expect(substr_count($header, 'data-nav-group="pages" data-active="true"'))->toBe(2)
        ->and(substr_count($header, 'href="'.route('pages.show', 'kvkk').'" aria-current="page"'))->toBe(2)
        ->and($header)->not->toContain('href="'.route('pages.show', 'cookie-policy').'" aria-current="page"');
});

it('does not mark the menu active elsewhere', function () {
    Page::factory()->published()->create();

    expect(headerHtml(route('blog')))->toContain('data-nav-group="pages" data-active="false"')
        ->not->toContain('data-nav-group="pages" data-active="true"');
});

it('starts the mobile submenu expanded on a static page', function () {
    $page = Page::factory()->published()->create();

    expect(headerHtml(route('pages.show', $page)))->toMatch('/data-nav-group="pages"[^>]*data-mobile-collapse>.*?<div[^>]*style="max-height: none"/s');
});

it('still renders on the 404 of an unknown static page, where the route parameter is not a model', function () {
    $this->get('/sayfa/olmayan-sayfa')->assertNotFound();
});
