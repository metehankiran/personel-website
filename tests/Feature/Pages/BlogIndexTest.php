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
        'reading_time' => 6,
    ]);

    $this->get(route('blog'))
        ->assertSee('Test Yazısı', escape: false)
        ->assertSee('Kısa açıklama', escape: false)
        ->assertSee('6 dk', escape: false)
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

it('displays tag filters from database', function () {
    $tag = Tag::factory()->create(['name' => 'PHP']);
    $post = Post::factory()->published()->for(Category::factory())->create();
    $post->tags()->attach($tag);

    $this->get(route('blog'))
        ->assertSee('PHP', escape: false);
});

it('shows newsletter subscription form', function () {
    $this->get(route('blog'))
        ->assertSee('newsletter', escape: false);
});
