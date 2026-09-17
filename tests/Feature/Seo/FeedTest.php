<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://example.test']);
    URL::forceRootUrl('https://example.test');

    $general = app(GeneralSettings::class);
    $general->site_title = 'Ada Studio';
    $general->site_description = 'Yazılım üzerine notlar.';
    $general->save();
});

function feed(): SimpleXMLElement
{
    $response = test()->get('/feed.xml')->assertOk();

    expect($response->headers->get('Content-Type'))->toStartWith('application/rss+xml');

    return simplexml_load_string($response->getContent());
}

it('serves a well formed rss feed', function () {
    $channel = feed()->channel;

    expect((string) $channel->title)->toBe('Ada Studio')
        ->and((string) $channel->link)->toBe('https://example.test/blog')
        ->and((string) $channel->description)->toBe('Yazılım üzerine notlar.')
        ->and((string) $channel->language)->toBe('tr-TR')
        ->and(route('feed', absolute: false))->toBe('/feed.xml');
});

it('lists published posts newest first with absolute urls', function () {
    $category = Category::factory()->create(['name' => 'Mimari']);
    $older = Post::factory()->published()->for($category)->create(['slug' => 'eski-yazi', 'published_at' => '2026-01-10 09:00:00']);
    $newer = Post::factory()->published()->for($category)->create([
        'title' => 'Kuyruklar & İşler',
        'slug' => 'yeni-yazi',
        'excerpt' => 'Kısa özet.',
        'published_at' => '2026-03-05 10:00:00',
    ]);

    $items = feed()->channel->item;

    expect($items)->toHaveCount(2)
        ->and((string) $items[0]->title)->toBe('Kuyruklar & İşler')
        ->and((string) $items[0]->link)->toBe('https://example.test/blog/yeni-yazi')
        ->and((string) $items[0]->guid)->toBe('https://example.test/blog/yeni-yazi')
        ->and((string) $items[0]->description)->toBe('Kısa özet.')
        ->and((string) $items[0]->category)->toBe('Mimari')
        ->and((string) $items[0]->pubDate)->toBe($newer->published_at->toRssString())
        ->and((string) $items[1]->link)->toBe('https://example.test/blog/'.$older->slug);
});

it('leaves drafts and scheduled posts out of the feed', function () {
    $category = Category::factory()->create();
    Post::factory()->for($category)->create(['title' => 'Gizli Taslak', 'is_published' => false]);
    Post::factory()->for($category)->create(['title' => 'Yarının Yazısı', 'is_published' => true, 'published_at' => now()->addDay()]);

    expect(feed()->channel->item)->toHaveCount(0);
});

it('limits the feed to the latest twenty posts', function () {
    Post::factory()->published()->for(Category::factory())->count(22)->create();

    expect(feed()->channel->item)->toHaveCount(20);
});

it('advertises the feed on every page', function (string $route) {
    $this->get(route($route))
        ->assertSee('<link rel="alternate" type="application/rss+xml" title="Ada Studio — Blog" href="https://example.test/feed.xml">', escape: false);
})->with(['home', 'blog', 'contact']);

it('points language models at the feed', function () {
    expect($this->get('/llms.txt')->getContent())->toContain('- [RSS](https://example.test/feed.xml)');
});
