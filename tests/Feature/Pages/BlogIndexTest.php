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
    $category = Category::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
    $tag = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
    Post::factory()->published()->for($category)->hasAttached($tag)->create();

    $this->get(route('blog'))
        ->assertSee(route('blog.category', 'laravel'), escape: false)
        ->assertSee(route('blog.tag', 'php'), escape: false);
});

it('hides categories and tags without published posts from the sidebar', function () {
    $draftOnly = Category::factory()->create(['slug' => 'taslak-kategori']);
    $draftTag = Tag::factory()->create(['slug' => 'taslak-etiket']);
    Post::factory()->for($draftOnly)->hasAttached($draftTag)->create(['is_published' => false]);
    Category::factory()->create(['slug' => 'bos-kategori']);
    Tag::factory()->create(['slug' => 'bos-etiket']);

    $this->get(route('blog'))
        ->assertDontSee(route('blog.category', 'taslak-kategori'), escape: false)
        ->assertDontSee(route('blog.category', 'bos-kategori'), escape: false)
        ->assertDontSee(route('blog.tag', 'taslak-etiket'), escape: false)
        ->assertDontSee(route('blog.tag', 'bos-etiket'), escape: false);
});

it('still shows the active category or tag on its own empty listing', function () {
    $category = Category::factory()->create(['slug' => 'bos-kategori']);
    $tag = Tag::factory()->create(['slug' => 'bos-etiket']);

    $this->get(route('blog.category', $category))->assertSee(route('blog.category', 'bos-kategori'), escape: false);
    $this->get(route('blog.tag', $tag))->assertSee(route('blog.tag', 'bos-etiket'), escape: false);
});

it('shows newsletter subscription form', function () {
    $this->get(route('blog'))
        ->assertSee('action="'.route('newsletter.subscribe').'"', escape: false);
});
