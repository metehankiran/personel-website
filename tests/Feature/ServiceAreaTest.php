<?php

declare(strict_types=1);

use App\Models\Service;
use App\Models\ServiceArea;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://example.test']);
    URL::forceRootUrl('https://example.test');
});

/**
 * Every JSON-LD node on the page, flattened out of any @graph wrapper.
 */
function serviceAreaSchemaNodes(string $url): Collection
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', test()->get($url)->assertOk()->getContent(), $matches);

    return collect($matches[1])
        ->map(fn (string $json): array => json_decode($json, true, flags: JSON_THROW_ON_ERROR))
        ->flatMap(fn (array $data): array => $data['@graph'] ?? [$data]);
}

it('ships Kütahya and all of its districts as default content', function () {
    expect(ServiceArea::ordered()->pluck('name')->all())->toBe([
        'Kütahya Merkez', 'Tavşanlı', 'Simav', 'Gediz', 'Emet', 'Şaphane', 'Altıntaş',
        'Domaniç', 'Hisarcık', 'Aslanapa', 'Çavdarhisar', 'Dumlupınar', 'Pazarlar',
    ])
        ->and(ServiceArea::pluck('province')->unique()->all())->toBe(['Kütahya'])
        ->and(ServiceArea::whereNull('description')->count())->toBe(0);
});

it('does not seed the defaults again over content the owner already manages', function () {
    ServiceArea::query()->delete();
    ServiceArea::factory()->create(['name' => 'Benim bölgem']);

    (require database_path('migrations/2026_09_18_134942_seed_default_service_areas.php'))->up();

    expect(ServiceArea::pluck('name')->all())->toBe(['Benim bölgem']);
});

describe('page', function () {
    beforeEach(fn () => ServiceArea::query()->delete());

    it('is served from a Turkish url', function () {
        expect(route('service-areas', absolute: false))->toBe('/hizmet-bolgeleri');
    });

    it('lists the published areas in order, each heading linking to its own page', function () {
        $simav = ServiceArea::factory()->create(['name' => 'Simav', 'province' => 'Kütahya', 'summary' => 'Termal turizm.', 'sectors' => ['Termal oteller', 'Seracılık'], 'sort_order' => 2]);
        ServiceArea::factory()->create(['name' => 'Tavşanlı', 'province' => 'Kütahya', 'summary' => 'Sanayi ve ticaret.', 'sort_order' => 1]);
        ServiceArea::factory()->create(['name' => 'Gizli ilçe', 'is_published' => false]);

        $html = $this->get(route('service-areas'))
            ->assertOk()
            ->assertSeeInOrder(['Tavşanlı', 'Sanayi ve ticaret.', 'Simav', 'Termal turizm.', 'Termal oteller', 'Seracılık'])
            ->assertDontSee('Gizli ilçe')
            ->getContent();

        expect($html)->toMatch('#<h2[^>]*>\s*<a href="'.preg_quote(route('service-areas.show', $simav), '#').'"[^>]*>\s*Simav\s*</a>\s*</h2>#u')
            ->toMatch('#<h1[^>]*>[^<]*Kütahya[^<]*</h1>#u');
    });

    it('keeps the long description for the area page, so the same text is not published twice', function () {
        ServiceArea::factory()->create(['description' => 'Eynal kaplıcaları çevresindeki oteller.']);

        $this->get(route('service-areas'))->assertDontSee('Eynal kaplıcaları çevresindeki oteller.');
    });

    it('names the provinces in the title and the description', function () {
        ServiceArea::factory()->create(['name' => 'Simav', 'province' => 'Kütahya', 'sort_order' => 1]);
        ServiceArea::factory()->create(['name' => 'Banaz', 'province' => 'Uşak', 'sort_order' => 2]);

        $html = $this->get(route('service-areas'))->getContent();

        expect($html)->toMatch('#<title>[^<]*Kütahya ve Uşak[^<]*</title>#u')
            ->toMatch('#<meta name="description" content="[^"]*Simav[^"]*"#u');
    });

    it('links every area to the contact page', function () {
        ServiceArea::factory()->create();

        $this->get(route('service-areas'))->assertSee('href="'.route('contact').'"', escape: false);
    });

    it('shows an empty state when there is no area', function () {
        $this->get(route('service-areas'))->assertOk()->assertSee('Henüz bir hizmet bölgesi eklenmedi');
    });

    it('is linked from the footer', function () {
        expect($this->get(route('home'))->getContent())->toContain('href="'.route('service-areas').'"');
    });

    it('is introduced on the services page once there is an area to show', function () {
        $this->get(route('services'))->assertDontSee('Hizmet verdiğim bölgeler');

        ServiceArea::factory()->create(['name' => 'Simav', 'province' => 'Kütahya']);

        $this->get(route('services'))->assertSee('Hizmet verdiğim bölgeler')->assertSee('Kütahya');
    });
});

describe('area page', function () {
    beforeEach(fn () => ServiceArea::query()->delete());

    it('gives every area its own Turkish url', function () {
        $area = ServiceArea::factory()->create(['name' => 'Çavdarhisar']);

        expect(route('service-areas.show', $area, absolute: false))->toBe('/hizmet-bolgeleri/cavdarhisar');
    });

    it('shows the local content of the area under a heading that names it', function () {
        $area = ServiceArea::factory()->create(['name' => 'Simav', 'province' => 'Kütahya', 'summary' => 'Termal turizm.', 'description' => "Eynal kaplıcaları çevresindeki oteller.\nSeracılık da var.", 'sectors' => ['Termal oteller', 'Seracılık']]);

        $html = $this->get(route('service-areas.show', $area))
            ->assertOk()
            ->assertSeeInOrder(['Termal turizm.', 'Eynal kaplıcaları', 'Termal oteller', 'Seracılık'])
            ->assertSee("Eynal kaplıcaları çevresindeki oteller.<br />\nSeracılık da var.", escape: false)
            ->assertSee('href="'.route('contact').'"', escape: false)
            ->getContent();

        expect($html)->toMatch('#<h1[^>]*>\s*Simav web tasarım ve yazılım\.\s*</h1>#u')
            ->toMatch('#<title>Simav Web Tasarım ve Yazılım[^<]*</title>#u')
            ->toMatch('#<meta name="description" content="[^"]*Termal turizm\.[^"]*"#u');
    });

    it('lists the services on offer and links to them', function () {
        $area = ServiceArea::factory()->create();
        Service::factory()->create(['title' => 'Kurumsal web sitesi', 'description' => 'Sıfırdan tasarım ve geliştirme.']);

        $this->get(route('service-areas.show', $area))
            ->assertSeeInOrder(['Kurumsal web sitesi', 'Sıfırdan tasarım ve geliştirme.'])
            ->assertSee('href="'.route('services').'"', escape: false);
    });

    it('links to the other published areas and back to the list', function () {
        $area = ServiceArea::factory()->create(['name' => 'Simav']);
        $other = ServiceArea::factory()->create(['name' => 'Gediz']);
        $hidden = ServiceArea::factory()->create(['name' => 'Gizli ilçe', 'is_published' => false]);

        $main = Str::between($this->get(route('service-areas.show', $area))->getContent(), '<main', '</main>');

        expect($main)->toContain('href="'.route('service-areas.show', $other).'"')
            ->toContain('href="'.route('service-areas').'"')
            ->not->toContain('href="'.route('service-areas.show', $area).'"')
            ->not->toContain('href="'.route('service-areas.show', $hidden).'"');
    });

    it('does not exist for an unpublished or unknown area', function () {
        $hidden = ServiceArea::factory()->create(['is_published' => false]);

        $this->get(route('service-areas.show', $hidden))->assertNotFound();
        $this->get('/hizmet-bolgeleri/olmayan-ilce')->assertNotFound();
    });

    it('describes the service offered in that area, with a breadcrumb under the list', function () {
        $general = app(GeneralSettings::class);
        $general->author_location = 'Kütahya, TR';
        $general->save();

        $area = ServiceArea::factory()->create(['name' => 'Simav', 'province' => 'Kütahya', 'summary' => 'Termal turizm.']);
        ServiceArea::factory()->create(['name' => 'Gediz']);

        $nodes = serviceAreaSchemaNodes(route('service-areas.show', $area));

        expect($nodes->firstWhere('@type', 'Service'))->toMatchArray([
            'name' => 'Simav web tasarım ve yazılım',
            'description' => 'Termal turizm.',
            'url' => 'https://example.test/hizmet-bolgeleri/simav',
            'provider' => ['@id' => 'https://example.test#business'],
            'areaServed' => ['@type' => 'AdministrativeArea', 'name' => 'Simav', 'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Kütahya']],
        ])
            ->and($nodes->firstWhere('@type', 'ProfessionalService'))->not->toBeNull()
            ->and(array_column($nodes->firstWhere('@type', 'BreadcrumbList')['itemListElement'], 'name'))->toBe(['Ana sayfa', 'Hizmet Bölgeleri', 'Simav']);
    });
});

describe('structured data', function () {
    beforeEach(function () {
        ServiceArea::query()->delete();

        $general = app(GeneralSettings::class);
        $general->author_name = 'Ada Yazar';
        $general->author_title = 'Full-stack Developer';
        $general->author_phone = '+90 555 000 00 00';
        $general->author_location = 'Kütahya, TR';
        $general->save();
    });

    it('describes the owner as a professional service that serves each province and district', function () {
        ServiceArea::factory()->create(['name' => 'Simav', 'province' => 'Kütahya', 'sort_order' => 2]);
        ServiceArea::factory()->create(['name' => 'Tavşanlı', 'province' => 'Kütahya', 'sort_order' => 1]);
        ServiceArea::factory()->create(['name' => 'Gizli ilçe', 'is_published' => false]);

        $business = serviceAreaSchemaNodes(route('service-areas'))->firstWhere('@type', 'ProfessionalService');

        expect($business)->toMatchArray([
            '@id' => 'https://example.test#business',
            'name' => 'Ada Yazar',
            'url' => 'https://example.test',
            'telephone' => '+90 555 000 00 00',
            'founder' => ['@id' => 'https://example.test#person'],
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Kütahya', 'addressCountry' => 'TR'],
        ])
            ->and($business['areaServed'])->toBe([
                ['@type' => 'AdministrativeArea', 'name' => 'Kütahya'],
                ['@type' => 'AdministrativeArea', 'name' => 'Tavşanlı', 'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Kütahya']],
                ['@type' => 'AdministrativeArea', 'name' => 'Simav', 'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Kütahya']],
            ]);
    });

    it('publishes the professional service on the home page too', function () {
        ServiceArea::factory()->create();

        expect(serviceAreaSchemaNodes(route('home'))->firstWhere('@type', 'ProfessionalService'))->not->toBeNull();
    });

    it('publishes no professional service without an area to serve', function () {
        expect(serviceAreaSchemaNodes(route('service-areas'))->firstWhere('@type', 'ProfessionalService'))->toBeNull()
            ->and(serviceAreaSchemaNodes(route('home'))->firstWhere('@type', 'ProfessionalService'))->toBeNull();
    });

    it('adds the served areas to every service', function () {
        ServiceArea::factory()->create(['name' => 'Simav', 'province' => 'Kütahya']);
        Service::factory()->count(2)->create();

        $services = serviceAreaSchemaNodes(route('services'))->firstWhere('@type', 'ItemList')['itemListElement'];

        expect($services)->toHaveCount(2)
            ->and(array_column($services[0]['item']['areaServed'], 'name'))->toBe(['Kütahya', 'Simav'])
            ->and(array_column($services[1]['item']['areaServed'], 'name'))->toBe(['Kütahya', 'Simav']);
    });

    it('leaves the services without an area when none is published', function () {
        Service::factory()->create();

        $services = serviceAreaSchemaNodes(route('services'))->firstWhere('@type', 'ItemList')['itemListElement'];

        expect($services[0]['item'])->not->toHaveKey('areaServed');
    });

    it('adds a breadcrumb to the page', function () {
        $breadcrumbs = serviceAreaSchemaNodes(route('service-areas'))->firstWhere('@type', 'BreadcrumbList');

        expect(collect($breadcrumbs['itemListElement'])->last())->toMatchArray(['name' => 'Hizmet Bölgeleri', 'item' => 'https://example.test/hizmet-bolgeleri']);
    });
});

describe('crawler files', function () {
    beforeEach(fn () => ServiceArea::query()->delete());

    it('lists the page in the sitemap and llms.txt once it has content', function () {
        ServiceArea::factory()->create();

        ServiceArea::factory()->create(['name' => 'Simav']);

        expect($this->get(route('sitemap'))->getContent())->toContain('<loc>https://example.test/hizmet-bolgeleri</loc>')
            ->toContain('<loc>https://example.test/hizmet-bolgeleri/simav</loc>')
            ->and($this->get(route('llms'))->getContent())->toContain('https://example.test/hizmet-bolgeleri');
    });

    it('keeps the empty page out of the sitemap and llms.txt', function () {
        ServiceArea::factory()->create(['name' => 'Simav', 'is_published' => false]);

        expect($this->get(route('sitemap'))->getContent())->not->toContain('hizmet-bolgeleri')
            ->and($this->get(route('llms'))->getContent())->not->toContain('hizmet-bolgeleri');
    });
});
