<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
