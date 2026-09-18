<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use Database\Seeders\LocalSeoPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(fn () => Storage::fake('public'));

it('adds the local guides as drafts in their own category, for the owner to review and publish', function () {
    $this->seed(LocalSeoPostSeeder::class);

    $posts = Category::firstWhere('slug', 'kutahya')->posts;

    expect($posts->pluck('slug')->all())->toEqualCanonicalizing([
        'kutahya-web-sitesi-yaptirmak',
        'cini-seramik-e-ticaret-rehberi',
        'kutahya-google-isletme-profili-rehberi',
        'termal-otel-web-sitesi-rehberi',
        'web-sitesi-fiyatlari-neye-gore-degisir',
    ])
        ->and(Post::published()->count())->toBe(0);

    $posts->each(function (Post $post) {
        expect($post->excerpt)->not->toBeEmpty()
            ->and(mb_strlen($post->excerpt))->toBeLessThanOrEqual(160, "{$post->slug} excerpt doubles as the meta description")
            ->and(count(preg_split('/\s+/u', trim(strip_tags($post->body)))))->toBeGreaterThan(450, "{$post->slug} is too thin to rank")
            ->and($post->body)->toContain('<h2>')
            ->and($post->tags)->not->toBeEmpty();
    });
});

it('links every guide to pages that exist, through the router rather than a hardcoded path', function () {
    $this->seed(LocalSeoPostSeeder::class);

    Post::all()->each(function (Post $post) {
        preg_match_all('#href="([^"]+)"#', $post->body, $links);

        expect($post->body)->not->toContain('{{')
            ->and($links[1])->not->toBeEmpty("{$post->slug} has no internal link")
            ->and($post->body)->toContain('href="'.route('service-areas', absolute: false).'"');

        foreach ($links[1] as $href) {
            expect($href)->toStartWith('/', "{$href} in {$post->slug} should be a relative internal link");

            // Drafts are not public yet, so a link to another guide is checked against the database instead.
            str_starts_with($href, '/blog/')
                ? expect(Post::where('slug', basename($href))->exists())->toBeTrue("{$href} in {$post->slug} points to no post")
                : $this->get($href)->assertOk();
        }
    });
});

it('can run again without duplicating a guide or overwriting what the owner edited', function () {
    $this->seed(LocalSeoPostSeeder::class);

    Post::firstWhere('slug', 'kutahya-web-sitesi-yaptirmak')->update(['title' => 'Benim başlığım']);

    $this->seed(LocalSeoPostSeeder::class);

    expect(Post::count())->toBe(5)
        ->and(Category::where('slug', 'kutahya')->count())->toBe(1)
        ->and(Post::firstWhere('slug', 'kutahya-web-sitesi-yaptirmak')->title)->toBe('Benim başlığım');
});
