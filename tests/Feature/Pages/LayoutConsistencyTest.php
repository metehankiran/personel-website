<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

const SITE_CONTAINER = 'max-w-7xl mx-auto px-6 lg:px-12';

it('lays the cv out in the same container as the other pages', function () {
    $this->get(route('cv'))
        ->assertOk()
        ->assertSee(SITE_CONTAINER, escape: false)
        ->assertDontSee('max-w-[920px]', escape: false);
});

it('lays static pages out in the same container as the other pages', function () {
    $page = Page::factory()->published()->create();

    $this->get(route('pages.show', $page))
        ->assertOk()
        ->assertSee(SITE_CONTAINER, escape: false)
        ->assertDontSee('max-w-[920px]', escape: false);
});

it('uses the shared container on every top level page', function (string $route) {
    $this->get(route($route))->assertSee(SITE_CONTAINER, escape: false);
})->with(['home', 'about', 'services', 'references', 'stack', 'projects', 'bookmarks', 'blog', 'contact', 'cv', 'faq']);

it('calls the stack page "Teknolojiler" everywhere a visitor reads it', function () {
    $html = $this->get(route('stack'))->assertOk()->getContent();

    expect($html)->toContain('<title>Teknolojiler — ')
        ->and(substr_count($html, 'Teknolojiler'))->toBeGreaterThanOrEqual(4); // title, eyebrow, header (desktop + mobile), footer
});

it('no longer labels anything "Stack" in the navigation, footer, 404 page or search index', function () {
    $files = [
        resource_path('views/components/site-header.blade.php'),
        resource_path('views/components/site-footer.blade.php'),
        resource_path('views/errors/404.blade.php'),
        resource_path('views/pages/stack.blade.php'),
        app_path('Http/Controllers/SearchController.php'),
    ];

    foreach ($files as $file) {
        // "Full-stack" and route names such as 'stack' are fine; a capitalised standalone label is not.
        expect(preg_match('/(?<![\w-])Stack(?![\w-])/u', File::get($file)))->toBe(0, basename($file).' still says "Stack"');
    }
});

it('lists the page as "Teknolojiler" in the search index', function () {
    $titles = collect($this->getJson(route('search.index'))->json())->pluck('title');

    expect($titles)->toContain('Teknolojiler')->not->toContain('Stack');
});

it('styles links in a static page body wherever the rich editor nests them', function () {
    $page = Page::factory()->published()->create(['body' => '<p>Başvurular için <a href="mailto:a@example.com">a@example.com</a> adresine yazın.</p>']);

    $html = $this->get(route('pages.show', $page))->assertOk()->getContent();

    // "[&>a]" only matches anchors that are direct children of the body, which rich text never produces.
    expect($html)->toContain('[&_a]:underline')->not->toContain('[&>a]:');
});

it('gives static page bodies a comfortable reading width instead of a narrow column', function () {
    $page = Page::factory()->published()->create();

    $this->get(route('pages.show', $page))
        ->assertSee('max-w-[760px]', escape: false)
        ->assertDontSee('max-w-[640px]', escape: false);
});
