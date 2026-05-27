<?php

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('page can be created with valid attributes', function () {
    $page = Page::factory()->create();

    expect($page)->toBeInstanceOf(Page::class)
        ->and($page->title)->toBeString()
        ->and($page->slug)->toBeString()
        ->and($page->body)->toBeString()
        ->and($page->is_published)->toBeBool();
});

test('page factory can create published state', function () {
    $page = Page::factory()->published()->create();

    expect($page->is_published)->toBeTrue();
});

test('published scope returns only published pages', function () {
    Page::factory()->published()->count(2)->create();
    Page::factory()->create(['is_published' => false]);

    expect(Page::published()->count())->toBe(2);
});

test('page seeder runs without errors', function () {
    $this->seed(PageSeeder::class);

    expect(Page::count())->toBeGreaterThan(0);
});
