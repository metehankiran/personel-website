<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Support\Seo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://example.test']);
    URL::forceRootUrl('https://example.test');
});

it('builds the canonical url from the configured site url, not the requested host', function () {
    // Not the www twin (that one is redirected), but any other host the app may answer on.
    $this->get('https://staging.example.test/hakkimda?utm_source=x')
        ->assertOk()
        ->assertSee('<link rel="canonical" href="https://example.test/hakkimda">', escape: false);
});

it('uses the bare site url as the canonical of the home page', function () {
    $this->get('/')->assertSee('<link rel="canonical" href="https://example.test">', escape: false);
});

it('prints og:url matching the canonical url', function () {
    $this->get('/hizmetler')->assertSee('<meta property="og:url" content="https://example.test/hizmetler">', escape: false);
});

it('always gives social networks an absolute image url', function () {
    Storage::fake('public');
    Storage::disk('public')->put('covers/kapak.png', 'png');

    $post = Post::factory()->published()->for(Category::factory())->create(['cover_image' => 'covers/kapak.png']);

    $html = $this->get(route('blog.show', $post))->getContent();

    expect($html)->toContain('<meta property="og:image" content="https://example.test/storage/covers/kapak.png">')
        ->and($html)->toContain('<meta name="twitter:image" content="https://example.test/storage/covers/kapak.png">')
        ->and($html)->not->toContain('<meta property="og:image" content="/storage');
});

it('makes urls absolute without touching ones that already are', function () {
    expect(Seo::absolute('/storage/a.png'))->toBe('https://example.test/storage/a.png')
        ->and(Seo::absolute('storage/a.png'))->toBe('https://example.test/storage/a.png')
        ->and(Seo::absolute('https://cdn.example.com/a.png'))->toBe('https://cdn.example.com/a.png');
});

it('escapes special characters in titles exactly once', function () {
    $post = Post::factory()->published()->for(Category::factory())->create(['title' => "Plesk'te Kuyruklar & İşler"]);

    $this->get(route('blog.show', $post))
        ->assertSee('<title>Plesk&#039;te Kuyruklar &amp; İşler — ', escape: false)
        ->assertSee('<meta property="og:title" content="Plesk&#039;te Kuyruklar &amp; İşler — ', escape: false);
});

it('lets normal pages be indexed', function () {
    $this->get('/')->assertSee('<meta name="robots" content="index, follow">', escape: false);
});

it('keeps error pages out of the index', function () {
    $this->get('/yok-boyle-bir-sayfa')
        ->assertNotFound()
        ->assertSee('<meta name="robots" content="noindex, follow">', escape: false)
        ->assertDontSee('content="index, follow"', escape: false);
});

it('keeps previews of unpublished content out of the index', function () {
    $post = Post::factory()->for(Category::factory())->create(['is_published' => false]);

    $this->actingAs(User::factory()->create())
        ->get(route('blog.show', $post))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, follow">', escape: false);
});

it('keeps category and tag listings without published posts out of the index', function () {
    $draftOnly = Category::factory()->create();
    Post::factory()->for($draftOnly)->create(['is_published' => false]);

    foreach ([route('blog.category', Category::factory()->create()), route('blog.tag', Tag::factory()->create()), route('blog.category', $draftOnly)] as $url) {
        $this->get($url)
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', escape: false)
            ->assertDontSee('content="index, follow"', escape: false);
    }
});

it('lets category and tag listings with published posts be indexed', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create();
    Post::factory()->published()->for($category)->hasAttached($tag)->create();

    foreach ([route('blog.category', $category), route('blog.tag', $tag), route('blog')] as $url) {
        $this->get($url)->assertSee('<meta name="robots" content="index, follow">', escape: false);
    }
});

it('marks publish dates up so machines can read them', function () {
    $post = Post::factory()->published()->for(Category::factory())->create(['published_at' => '2026-03-05 10:00:00']);

    foreach ([route('blog.show', $post), route('blog'), route('home')] as $url) {
        $this->get($url)->assertSee('<time datetime="2026-03-05T10:00:00+03:00"', escape: false);
    }
});

it('labels a preview without a publish date as a draft instead of crashing', function () {
    $post = Post::factory()->for(Category::factory())->create(['is_published' => false, 'published_at' => null]);

    $this->actingAs(User::factory()->create())
        ->get(route('blog.show', $post))
        ->assertOk()
        ->assertSee('Taslak');
});
