<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

beforeEach(function () {
    $general = app(GeneralSettings::class);
    $general->site_title = 'Ada Studio';
    $general->author_name = 'Ada Yazar';
    $general->author_title = 'Developer';
    $general->save();
});

it('suffixes every page title with the site title from the settings', function (Closure $url, string $expected) {
    $this->get($url())->assertOk()->assertSee("<title>{$expected}</title>", escape: false);
})->with([
    'services' => [fn () => route('services'), 'Hizmetler — Ada Studio'],
    'stack' => [fn () => route('stack'), 'Teknolojiler — Ada Studio'],
    'references' => [fn () => route('references'), 'Referanslar — Ada Studio'],
    'bookmarks' => [fn () => route('bookmarks'), 'Yer İşaretlerim — Ada Studio'],
    'projects' => [fn () => route('projects'), 'Projeler — Ada Studio'],
    'project' => [fn () => route('projects.show', Project::factory()->create(['title' => 'Karavela'])), 'Karavela — Projeler — Ada Studio'],
    'blog' => [fn () => route('blog'), 'Blog — Ada Studio'],
    'post' => [fn () => route('blog.show', Post::factory()->published()->for(Category::factory())->create(['title' => 'İlk Yazı'])), 'İlk Yazı — Ada Studio'],
    'about' => [fn () => route('about'), 'Hakkımda — Ada Studio'],
    'cv' => [fn () => route('cv'), 'CV — Ada Studio'],
    'contact' => [fn () => route('contact'), 'İletişim — Ada Studio'],
]);

it('keeps the home page title as author name and title', function () {
    $this->get(route('home'))->assertSee('<title>Ada Yazar — Developer</title>', escape: false);
});

it('falls back to the app name when no site title is configured', function () {
    $general = app(GeneralSettings::class);
    $general->site_title = '';
    $general->save();

    $this->get(route('services'))->assertSee('<title>Hizmetler — '.config('app.name').'</title>', escape: false);
});

it('mirrors the title into the open graph tags and names the site', function () {
    $this->get(route('services'))
        ->assertSee('<meta property="og:title" content="Hizmetler — Ada Studio">', escape: false)
        ->assertSee('<meta property="og:site_name" content="Ada Studio">', escape: false);
});

it('shows the author name from the settings on blog posts', function () {
    $post = Post::factory()->published()->for(Category::factory())->create();

    $this->get(route('blog.show', $post))->assertSee('Ada Yazar');
});

it('does not hardcode the owner name in any public view', function () {
    $offenders = collect(File::allFiles(resource_path('views')))
        ->reject(fn ($file) => str_contains($file->getPathname(), '/vendor/'))
        ->filter(fn ($file) => preg_match('/metehan|k[ıi]ran/iu', $file->getContents()) === 1)
        ->map(fn ($file) => $file->getRelativePathname())
        ->values()
        ->all();

    expect($offenders)->toBe([]);
});

it('picks the meta description from seo settings, then the site description', function () {
    $general = app(GeneralSettings::class);
    $general->site_description = 'Site açıklaması.';
    $general->save();

    $this->get(route('services'))->assertSee('<meta name="description" content="Site açıklaması.">', escape: false);

    $seo = app(SeoSettings::class);
    $seo->meta_description = 'SEO açıklaması.';
    $seo->save();

    $this->get(route('services'))->assertSee('<meta name="description" content="SEO açıklaması.">', escape: false);
});

it('never renders an empty meta description', function () {
    $this->get(route('services'))
        ->assertDontSee('<meta name="description" content="">', escape: false)
        ->assertSee('<meta name="description" content="Ada Studio">', escape: false);
});

it('links every configured social profile in the footer', function () {
    $social = app(SocialSettings::class);
    $social->instagram_url = 'https://instagram.com/ada';
    $social->youtube_url = 'https://youtube.com/@ada';
    $social->bluesky_url = 'https://bsky.app/profile/ada';
    $social->save();

    $footer = Str::after($this->get(route('home'))->getContent(), 'role="contentinfo"');

    expect($footer)->toContain('https://instagram.com/ada')
        ->and($footer)->toContain('https://youtube.com/@ada')
        ->and($footer)->toContain('https://bsky.app/profile/ada');
});

it('leaves out social profiles that are not configured', function () {
    $footer = Str::after($this->get(route('home'))->getContent(), 'role="contentinfo"');

    expect($footer)->not->toContain('YouTube')->and($footer)->not->toContain('Bluesky');
});

it('lists youtube and bluesky on the contact page', function () {
    $social = app(SocialSettings::class);
    $social->youtube_url = 'https://youtube.com/@ada';
    $social->bluesky_url = 'https://bsky.app/profile/ada';
    $social->save();

    $this->get(route('contact'))
        ->assertSee('https://youtube.com/@ada', escape: false)
        ->assertSee('https://bsky.app/profile/ada', escape: false);
});

it('shows the address on the contact page and links it to the map when available', function () {
    $general = app(GeneralSettings::class);
    $general->author_address = 'Atatürk Blv. No:1, Kütahya';
    $general->save();

    $this->get(route('contact'))->assertSee('Atatürk Blv. No:1, Kütahya');

    $general->google_maps_url = 'https://maps.app.goo.gl/example';
    $general->save();

    $this->get(route('contact'))
        ->assertSee('Atatürk Blv. No:1, Kütahya')
        ->assertSee('href="https://maps.app.goo.gl/example"', escape: false);
});
