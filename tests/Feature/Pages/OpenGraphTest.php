<?php

declare(strict_types=1);

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('uses the default open graph image on static pages', function () {
    $this->get(route('home'))
        ->assertSee('<meta property="og:image" content="'.asset('images/og-default.png').'"', escape: false)
        ->assertSee('<meta name="twitter:image" content="'.asset('images/og-default.png').'"', escape: false);
});

it('uses the post cover as the open graph image when it exists', function () {
    Storage::disk('public')->put('covers/post.png', 'png');

    $post = Post::factory()->published()->create([
        'cover_image' => 'covers/post.png',
        'excerpt' => 'Kısa bir özet.',
    ]);

    $this->get(route('blog.show', $post))
        ->assertSee('<meta property="og:image" content="'.Storage::url('covers/post.png').'"', escape: false)
        ->assertSee('<meta name="description" content="Kısa bir özet."', escape: false);
});

it('falls back to the default open graph image when the post cover is broken', function () {
    $post = Post::factory()->published()->create(['cover_image' => 'covers/missing.png']);

    $this->get(route('blog.show', $post))
        ->assertSee('<meta property="og:image" content="'.asset('images/og-default.png').'"', escape: false);
});

it('renders the default cover instead of a broken image in related post cards', function () {
    $post = Post::factory()->published()->create();
    Post::factory()->published()->create([
        'category_id' => $post->category_id,
        'cover_image' => 'covers/missing.png',
    ]);

    $this->get(route('blog.show', $post))
        ->assertDontSee('/storage/covers/missing.png', escape: false)
        ->assertSee(asset('images/default-cover.svg'), escape: false);
});
