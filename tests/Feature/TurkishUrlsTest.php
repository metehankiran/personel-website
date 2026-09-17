<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Subscriber;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

it('serves the public pages under Turkish urls', function (string $route, string $path) {
    expect(route($route, absolute: false))->toBe($path);

    $this->get($path)->assertOk();
})->with([
    'home' => ['home', '/'],
    'about' => ['about', '/hakkimda'],
    'services' => ['services', '/hizmetler'],
    'references' => ['references', '/referanslar'],
    'projects' => ['projects', '/projeler'],
    'bookmarks' => ['bookmarks', '/yer-isaretlerim'],
    'contact' => ['contact', '/iletisim'],
    'faq' => ['faq', '/sss'],
    'blog' => ['blog', '/blog'],
    'stack' => ['stack', '/teknolojiler'],
    'cv' => ['cv', '/cv'],
    'search' => ['search', '/ara'],
]);

it('serves detail pages under Turkish urls', function () {
    $project = Project::factory()->create(['slug' => 'karavela']);
    $category = Category::factory()->create(['slug' => 'mimari']);
    $tag = Tag::factory()->create(['slug' => 'laravel']);
    $post = Post::factory()->published()->for($category)->create(['slug' => 'ilk-yazi']);
    $page = Page::factory()->published()->create(['slug' => 'kvkk']);

    expect(route('projects.show', $project, false))->toBe('/projeler/karavela')
        ->and(route('blog.category', $category, false))->toBe('/blog/kategori/mimari')
        ->and(route('blog.tag', $tag, false))->toBe('/blog/etiket/laravel')
        ->and(route('blog.show', $post, false))->toBe('/blog/ilk-yazi')
        ->and(route('pages.show', $page, false))->toBe('/sayfa/kvkk');

    foreach (['/projeler/karavela', '/blog/kategori/mimari', '/blog/etiket/laravel', '/blog/ilk-yazi', '/sayfa/kvkk'] as $path) {
        $this->get($path)->assertOk();
    }
});

it('no longer answers on the old English urls', function (string $path) {
    $this->get($path)->assertNotFound();
})->with(['/about', '/services', '/references', '/projects', '/bookmarks', '/contact', '/stack', '/blog/category/mimari', '/blog/tag/laravel', '/pages/kvkk']);

it('does not mistake the Turkish category and tag segments for a post slug', function () {
    Post::factory()->published()->for(Category::factory())->create(['slug' => 'kategori']);

    // A post may even be called "kategori"; the listing routes still win for two-segment urls.
    $this->get('/blog/kategori')->assertOk();
    $this->get('/blog/kategori/yok-boyle-bir-kategori')->assertNotFound();
});

it('uses Turkish urls for the newsletter links that readers see in emails', function () {
    $subscriber = Subscriber::factory()->create(['email' => 'reader@example.com']);

    $url = route('newsletter.unsubscribe', ['email' => $subscriber->email, 'token' => $subscriber->token], false);

    expect($url)->toStartWith('/bulten/abonelikten-cik/');

    $this->get($url)->assertRedirect(route('home'));

    expect($subscriber->fresh()->is_active)->toBeFalse();
});

it('keeps route names in English', function () {
    foreach (['about', 'services', 'references', 'projects', 'projects.show', 'bookmarks', 'contact', 'blog.category', 'blog.tag', 'pages.show'] as $name) {
        expect(Route::has($name))->toBeTrue();
    }
});
