<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('renders the references page', function () {
    $this->get(route('references'))
        ->assertOk()
        ->assertViewIs('pages.references');
});

it('shows references hero content', function () {
    $this->get(route('references'))
        ->assertSee('Referanslar', escape: false)
        ->assertSee('Birlikte çalıştığım insanlar', escape: false);
});

it('displays testimonials from database', function () {
    Testimonial::factory()->create([
        'name' => 'Selin Akın',
        'title' => 'Co-founder',
        'company' => 'Karavela',
        'body' => 'Sadece kod değil, ürün düşüncesi de katıyor.',
    ]);

    $this->get(route('references'))
        ->assertSee('Selin Akın', escape: false)
        ->assertSee('Co-founder, Karavela', escape: false)
        ->assertSee('ürün düşüncesi', escape: false);
});

it('displays brands from database', function () {
    Brand::factory()->create(['name' => 'Trendyol']);
    Brand::factory()->create(['name' => 'Migros']);

    $this->get(route('references'))
        ->assertSee('Trendyol', escape: false)
        ->assertSee('Migros', escape: false);
});

it('displays testimonials in order', function () {
    Testimonial::factory()->create(['name' => 'İkinci', 'sort_order' => 2]);
    Testimonial::factory()->create(['name' => 'Birinci', 'sort_order' => 1]);

    $response = $this->get(route('references'));

    $response->assertSeeInOrder(['Birinci', 'İkinci']);
});

it('features the first testimonial as a large quote', function () {
    Testimonial::factory()->create(['name' => 'Öne Çıkan', 'sort_order' => 1]);
    Testimonial::factory()->create(['name' => 'Sıradan', 'sort_order' => 2]);

    $response = $this->get(route('references'));

    expect(substr_count($response->getContent(), 'data-featured'))->toBe(1);
    $response->assertSeeInOrder(['data-featured', 'Öne Çıkan', 'Sıradan'], escape: false);
});

it('falls back to initials when a testimonial has no avatar', function () {
    Testimonial::factory()->create(['name' => 'Selin Akın', 'avatar' => null]);

    $this->get(route('references'))
        ->assertSee('>SA<', escape: false);
});

it('shows stars on featured and regular cards only when rated', function () {
    Testimonial::factory()->rated(5)->create(['sort_order' => 1]);
    Testimonial::factory()->rated(4)->create(['sort_order' => 2]);
    Testimonial::factory()->create(['sort_order' => 3]);

    $html = $this->get(route('references'))->assertOk()->getContent();

    expect($html)->toContain('aria-label="5 üzerinden 5"')
        ->toContain('aria-label="5 üzerinden 4"')
        ->and(substr_count($html, 'role="img" aria-label="5 üzerinden'))->toBe(2);
});

it('prints brand logos with their size so the grid does not shift', function () {
    Storage::fake('public');
    Storage::disk('public')->putFileAs('brands', UploadedFile::fake()->image('acme.png', 320, 80), 'acme.png');
    Brand::factory()->create(['name' => 'Acme', 'logo' => 'brands/acme.png']);

    expect($this->get(route('references'))->getContent())->toMatch('/<img[^>]*alt="Acme"[^>]*\\swidth="320" height="80"/');
});
