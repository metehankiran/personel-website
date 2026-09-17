<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the blog index page', function () {
    $this->get(route('blog'))
        ->assertOk()
        ->assertViewIs('pages.blog.index');
});

it('shows the blog hero content', function () {
    $this->get(route('blog'))
        ->assertSee('Yazılar', escape: false);
});

it('displays published posts from database', function () {
    $category = Category::factory()->create(['name' => 'Laravel']);
    Post::factory()->published()->for($category)->create([
        'title' => 'Test Yazısı',
        'excerpt' => 'Kısa açıklama',
    ]);

    $this->get(route('blog'))
        ->assertSee('Test Yazısı', escape: false)
        ->assertSee('Kısa açıklama', escape: false)
        ->assertSee('dk okuma', escape: false)
        ->assertSee('Laravel', escape: false);
});

it('does not display draft posts', function () {
    Post::factory()->for(Category::factory())->create([
        'title' => 'Taslak Yazı',
        'is_published' => false,
    ]);

    $this->get(route('blog'))
        ->assertDontSee('Taslak Yazı');
});

it('filters posts by category via url', function () {
    $laravel = Category::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
    $vue = Category::factory()->create(['name' => 'Vue', 'slug' => 'vue']);
    Post::factory()->published()->for($laravel)->create(['title' => 'Laravel Yazısı']);
    Post::factory()->published()->for($vue)->create(['title' => 'Vue Yazısı']);

    $this->get(route('blog.category', $laravel))
        ->assertOk()
        ->assertSee('Laravel Yazısı', escape: false)
        ->assertDontSee('Vue Yazısı');
});

it('filters posts by tag via url', function () {
    $tag = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
    $category = Category::factory()->create();
    $tagged = Post::factory()->published()->for($category)->create(['title' => 'PHP Yazısı']);
    $tagged->tags()->attach($tag);
    Post::factory()->published()->for($category)->create(['title' => 'Diğer Yazı']);

    $this->get(route('blog.tag', $tag))
        ->assertOk()
        ->assertSee('PHP Yazısı', escape: false)
        ->assertDontSee('Diğer Yazı');
});

it('shows category and tag links in sidebar', function () {
    Category::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
    Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);

    $this->get(route('blog'))
        ->assertSee(route('blog.category', 'laravel'), escape: false)
        ->assertSee(route('blog.tag', 'php'), escape: false);
});

it('shows newsletter subscription form', function () {
    $this->get(route('blog'))
        ->assertSee('action="'.route('newsletter.subscribe').'"', escape: false);
});
