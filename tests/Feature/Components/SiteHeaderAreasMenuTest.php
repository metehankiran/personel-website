<?php

declare(strict_types=1);

use App\Models\ServiceArea;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn () => ServiceArea::query()->delete());

/**
 * The header markup only, so page bodies and the footer cannot satisfy an assertion by accident.
 */
function areasMenuHeaderHtml(string $url): string
{
    return Str::between(test()->get($url)->assertOk()->getContent(), '<header', '</header>');
}

it('lists the published areas under a "Bölgeler" menu on desktop and mobile, in panel order', function () {
    $simav = ServiceArea::factory()->create(['name' => 'Simav', 'sort_order' => 2]);
    $gediz = ServiceArea::factory()->create(['name' => 'Gediz', 'sort_order' => 1]);
    ServiceArea::factory()->create(['name' => 'Gizli ilçe', 'is_published' => false]);

    $header = areasMenuHeaderHtml(route('home'));

    expect(substr_count($header, 'data-nav-group="areas"'))->toBe(2)
        ->and(substr_count($header, 'href="'.route('service-areas').'"'))->toBe(2)
        ->and(substr_count($header, 'href="'.route('service-areas.show', $simav).'"'))->toBe(2)
        ->and(substr_count($header, 'href="'.route('service-areas.show', $gediz).'"'))->toBe(2)
        ->and(strpos($header, 'Gediz'))->toBeLessThan(strpos($header, 'Simav'))
        ->and($header)->not->toContain('Gizli ilçe');
});

it('hides the menu while there is no published area', function () {
    ServiceArea::factory()->create(['is_published' => false]);

    expect(areasMenuHeaderHtml(route('home')))->not->toContain('data-nav-group="areas"');
});

it('marks the group and the area being viewed as current', function () {
    $simav = ServiceArea::factory()->create(['name' => 'Simav']);
    $gediz = ServiceArea::factory()->create(['name' => 'Gediz']);

    $header = areasMenuHeaderHtml(route('service-areas.show', $simav));

    expect(substr_count($header, 'data-nav-group="areas" data-active="true"'))->toBe(2)
        ->and(substr_count($header, 'href="'.route('service-areas.show', $simav).'" aria-current="page"'))->toBe(2)
        ->and($header)->not->toContain('href="'.route('service-areas.show', $gediz).'" aria-current="page"');
});

it('marks the list page as current on the list page only', function () {
    $area = ServiceArea::factory()->create();

    expect(substr_count(areasMenuHeaderHtml(route('service-areas')), 'href="'.route('service-areas').'" aria-current="page"'))->toBe(2)
        ->and(areasMenuHeaderHtml(route('service-areas.show', $area)))->not->toContain('href="'.route('service-areas').'" aria-current="page"')
        ->and(areasMenuHeaderHtml(route('home')))->toContain('data-nav-group="areas" data-active="false"');
});
