<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

// --- Category ---

test('category can be created', function () {
    $category = Category::factory()->create();

    expect($category)->toBeInstanceOf(Category::class)
        ->and($category->name)->toBeString()
        ->and($category->slug)->toBeString();
});

test('category can have a parent', function () {
    $parent = Category::factory()->create(['name' => 'Backend']);
    $child = Category::factory()->child($parent)->create(['name' => 'PHP']);

    expect($child->parent)->toBeInstanceOf(Category::class)
        ->and($child->parent->id)->toBe($parent->id);
});

test('category has many children', function () {
    $parent = Category::factory()
        ->has(Category::factory()->count(2), 'children')
        ->create();

    expect($parent->children)->toHaveCount(2);
});

test('categories are ordered by sort_order', function () {
    Category::factory()->create(['sort_order' => 3, 'name' => 'Third', 'slug' => 'third']);
    Category::factory()->create(['sort_order' => 1, 'name' => 'First', 'slug' => 'first']);
    Category::factory()->create(['sort_order' => 2, 'name' => 'Second', 'slug' => 'second']);

    $categories = Category::ordered()->get();

    expect($categories->pluck('name')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

// --- Tag ---

test('tag can be created', function () {
    $tag = Tag::factory()->create();

    expect($tag)->toBeInstanceOf(Tag::class)
        ->and($tag->name)->toBeString()
        ->and($tag->slug)->toBeString();
});

test('tags are ordered by sort_order', function () {
    Tag::factory()->create(['sort_order' => 3, 'name' => 'Third', 'slug' => 'third']);
    Tag::factory()->create(['sort_order' => 1, 'name' => 'First', 'slug' => 'first']);
    Tag::factory()->create(['sort_order' => 2, 'name' => 'Second', 'slug' => 'second']);

    $tags = Tag::ordered()->get();

    expect($tags->pluck('name')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

// --- Post ---

test('post can be created with valid attributes', function () {
    $post = Post::factory()->create();

    expect($post)->toBeInstanceOf(Post::class)
        ->and($post->title)->toBeString()
        ->and($post->slug)->toBeString()
        ->and($post->body)->toBeString()
        ->and($post->is_published)->toBeBool();
});

test('post belongs to a category', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->for($category)->create();

    expect($post->category)->toBeInstanceOf(Category::class)
        ->and($post->category->id)->toBe($category->id);
});

test('post has many tags', function () {
    $post = Post::factory()
        ->has(Tag::factory()->count(3))
        ->create();

    expect($post->tags)->toHaveCount(3);
});

test('post published_at is cast to datetime', function () {
    $post = Post::factory()->published()->create();

    expect($post->published_at)->toBeInstanceOf(Carbon::class);
});

test('post cover_image is nullable', function () {
    $post = Post::factory()->create(['cover_image' => null]);

    expect($post->cover_image)->toBeNull();
});

test('post excerpt is nullable', function () {
    $post = Post::factory()->create(['excerpt' => null]);

    expect($post->excerpt)->toBeNull();
});

test('post factory can create published state', function () {
    $post = Post::factory()->published()->create();

    expect($post->is_published)->toBeTrue()
        ->and($post->published_at)->not->toBeNull();
});

test('published scope returns only published posts', function () {
    Post::factory()->published()->count(2)->create();
    Post::factory()->create(['is_published' => false]);

    expect(Post::published()->count())->toBe(2);
});

test('post seeder runs without errors', function () {
    $this->seed(PostSeeder::class);

    expect(Post::count())->toBeGreaterThan(0);
});
