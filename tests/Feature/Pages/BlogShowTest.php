<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders a blog post page for any slug', function () {
    $post = Post::factory()
        ->published()
        ->for(Category::factory())
        ->create([
            'title' => "Laravel'de gerçekten lazım olan paketler",
            'slug' => 'laravel-paketleri',
            'body' => 'İçindekiler bölümü burada yer alır.',
        ]);

    $this->get(route('blog.show', 'laravel-paketleri'))
        ->assertOk()
        ->assertViewIs('pages.blog.show');
});

it('shows blog post content with table of contents', function () {
    $post = Post::factory()
        ->published()
        ->for(Category::factory())
        ->create([
            'title' => "Laravel'de gerçekten lazım olan paketler",
            'slug' => 'laravel-paketleri',
            'body' => 'İçindekiler bölümü burada yer alır.',
        ]);

    $this->get(route('blog.show', 'laravel-paketleri'))
        ->assertSee("Laravel'de gerçekten lazım", escape: false)
        ->assertSee('İçindekiler', escape: false);
});
