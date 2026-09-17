<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('search index endpoint returns json', function () {
    $this->getJson(route('search.index'))
        ->assertOk()
        ->assertJsonStructure([['type', 'title', 'desc', 'url']]);
});

test('search index includes static pages', function () {
    $response = $this->getJson(route('search.index'));

    $titles = collect($response->json())->pluck('title');
    expect($titles)->toContain('Ana sayfa', 'Hakkımda', 'İletişim', 'CV');
});

test('search index includes published blog posts', function () {
    Post::factory()->create([
        'title' => 'Laravel Test Yazısı',
        'slug' => 'laravel-test-yazisi',
        'excerpt' => 'Test açıklaması',
        'is_published' => true,
        'published_at' => now(),
    ]);
    Post::factory()->create([
        'title' => 'Taslak Yazı',
        'slug' => 'taslak-yazi',
        'is_published' => false,
    ]);

    $response = $this->getJson(route('search.index'));

    $titles = collect($response->json())->pluck('title');
    expect($titles)->toContain('Laravel Test Yazısı');
    expect($titles)->not->toContain('Taslak Yazı');
});

test('search index includes projects', function () {
    Project::factory()->create([
        'title' => 'Test Projesi',
        'slug' => 'test-projesi',
        'description' => 'Proje açıklaması',
    ]);

    $response = $this->getJson(route('search.index'));

    $titles = collect($response->json())->pluck('title');
    expect($titles)->toContain('Test Projesi');
});

test('search index includes published pages', function () {
    Page::factory()->published()->create([
        'title' => 'KVKK Aydınlatma',
        'slug' => 'kvkk',
    ]);
    Page::factory()->create([
        'title' => 'Taslak Sayfa',
        'slug' => 'taslak',
        'is_published' => false,
    ]);

    $response = $this->getJson(route('search.index'));

    $titles = collect($response->json())->pluck('title');
    expect($titles)->toContain('KVKK Aydınlatma');
    expect($titles)->not->toContain('Taslak Sayfa');
});

test('search index includes social links from settings', function () {
    $response = $this->getJson(route('search.index'));

    $types = collect($response->json())->pluck('type')->unique();
    expect($types)->toContain('Hızlı erişim');
});
