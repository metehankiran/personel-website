<?php

use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use Database\Seeders\BookmarkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('bookmark category can be created', function () {
    $category = BookmarkCategory::factory()->create();

    expect($category)->toBeInstanceOf(BookmarkCategory::class)
        ->and($category->name)->toBeString();
});

test('bookmark can be created with a category', function () {
    $category = BookmarkCategory::factory()->create();
    $bookmark = Bookmark::factory()->for($category, 'category')->create();

    expect($bookmark)->toBeInstanceOf(Bookmark::class)
        ->and($bookmark->title)->toBeString()
        ->and($bookmark->url)->toBeString()
        ->and($bookmark->category_id)->toBe($category->id);
});

test('bookmark belongs to a category', function () {
    $category = BookmarkCategory::factory()->create();
    $bookmark = Bookmark::factory()->for($category, 'category')->create();

    expect($bookmark->category)->toBeInstanceOf(BookmarkCategory::class)
        ->and($bookmark->category->id)->toBe($category->id);
});

test('category has many bookmarks', function () {
    $category = BookmarkCategory::factory()
        ->has(Bookmark::factory()->count(3))
        ->create();

    expect($category->bookmarks)->toHaveCount(3);
});

test('categories are ordered by sort_order', function () {
    BookmarkCategory::factory()->create(['sort_order' => 3, 'name' => 'Third']);
    BookmarkCategory::factory()->create(['sort_order' => 1, 'name' => 'First']);
    BookmarkCategory::factory()->create(['sort_order' => 2, 'name' => 'Second']);

    $categories = BookmarkCategory::ordered()->get();

    expect($categories->pluck('name')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('bookmarks are ordered by sort_order', function () {
    $category = BookmarkCategory::factory()->create();
    Bookmark::factory()->for($category, 'category')->create(['sort_order' => 3, 'title' => 'Third']);
    Bookmark::factory()->for($category, 'category')->create(['sort_order' => 1, 'title' => 'First']);
    Bookmark::factory()->for($category, 'category')->create(['sort_order' => 2, 'title' => 'Second']);

    $bookmarks = Bookmark::ordered()->get();

    expect($bookmarks->pluck('title')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('bookmark description is nullable', function () {
    $bookmark = Bookmark::factory()->create(['description' => null]);

    expect($bookmark->description)->toBeNull();
});

test('bookmark seeder runs without errors', function () {
    $this->seed(BookmarkSeeder::class);

    expect(BookmarkCategory::count())->toBeGreaterThan(0)
        ->and(Bookmark::count())->toBeGreaterThan(0);
});
