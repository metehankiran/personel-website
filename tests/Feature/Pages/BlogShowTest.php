<?php

declare(strict_types=1);

use App\Livewire\NewsletterForm;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders a blog post page', function () {
    $post = Post::factory()->published()->for(Category::factory())->create(['slug' => 'test-post']);

    $this->get(route('blog.show', $post))
        ->assertOk()
        ->assertViewIs('pages.blog.show');
});

it('shows blog post content', function () {
    $category = Category::factory()->create(['name' => 'Laravel']);
    $tag = Tag::factory()->create(['name' => 'PHP']);
    $post = Post::factory()->published()->for($category)->create([
        'title' => 'Test Başlık',
        'body' => 'Makale içeriği burada.',
    ]);
    $post->tags()->attach($tag);

    $this->get(route('blog.show', $post))
        ->assertSee('Test Başlık', escape: false)
        ->assertSee('Makale içeriği burada', escape: false)
        ->assertSee('Laravel', escape: false)
        ->assertSee('PHP', escape: false)
        ->assertSee('dk okuma', escape: false);
});

it('shows related posts', function () {
    $category = Category::factory()->create();
    $main = Post::factory()->published()->for($category)->create();
    $related = Post::factory()->published()->for($category)->create(['title' => 'İlgili Yazı']);

    $this->get(route('blog.show', $main))
        ->assertSee('İlgili Yazı', escape: false);
});

it('shows newsletter subscription form on post page', function () {
    $post = Post::factory()->published()->for(Category::factory())->create();

    $this->get(route('blog.show', $post))
        ->assertSeeLivewire(NewsletterForm::class)
        ->assertSee('Abone ol');
});
