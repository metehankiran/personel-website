<?php

declare(strict_types=1);

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the services page', function () {
    $this->get(route('services'))
        ->assertOk()
        ->assertViewIs('pages.services');
});

it('shows services hero content', function () {
    $this->get(route('services'))
        ->assertSee('Hizmetler', escape: false)
        ->assertSee('Freelance web geliştirme hizmetleri', escape: false);
});

it('displays services from database', function () {
    Service::factory()->create([
        'title' => 'Sıfırdan ürün',
        'description' => 'Fikrini production-hazır bir ürüne dönüştürürüm.',
        'features' => ['Veritabanı tasarımı', 'API + frontend'],
        'pricing' => '8 hafta',
    ]);

    $this->get(route('services'))
        ->assertSee('Sıfırdan ürün', escape: false)
        ->assertSee('Fikrini production-hazır', escape: false)
        ->assertSee('Veritabanı tasarımı', escape: false)
        ->assertSee('API + frontend', escape: false);
});

it('displays service badge text', function () {
    Service::factory()->create([
        'title' => 'Mevcut ürüne devam',
        'badge' => 'En çok tercih edilen',
    ]);

    $this->get(route('services'))
        ->assertSee('En çok tercih edilen', escape: false);
});

it('displays services in order', function () {
    Service::factory()->create(['title' => 'İkinci', 'sort_order' => 2]);
    Service::factory()->create(['title' => 'Birinci', 'sort_order' => 1]);

    $response = $this->get(route('services'));

    $response->assertSeeInOrder(['Birinci', 'İkinci']);
});
