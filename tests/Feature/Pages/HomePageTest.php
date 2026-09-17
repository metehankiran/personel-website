<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\Project;
use App\Models\Testimonial;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Vite;

uses(RefreshDatabase::class);

it('renders the home page with a 200 status', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertViewIs('pages.home');
});

it('extends the app layout including header and footer', function () {
    $response = $this->get(route('home'));

    $response->assertSee('role="banner"', escape: false);
    $response->assertSee('role="contentinfo"', escape: false);
});

it('loads the required scripts from the public theme directory', function () {
    // Ignore a running Vite dev server so the built manifest is always used.
    Vite::useHotFile(storage_path('framework/testing/vite.hot'));

    $response = $this->get(route('home'));

    $response->assertSee('build/assets/app-', escape: false);
});

it('renders dynamic hero content from settings', function () {
    $settings = app(GeneralSettings::class);
    $settings->hero_title = 'Bağımsız <em>full-stack</em> developer.';
    $settings->availability_status = 'Yeni proje alıyor';
    $settings->save();

    $this->get(route('home'))
        ->assertSee('full-stack', escape: false)
        ->assertSee('Yeni proje alıyor', escape: false);
});

it('renders homepage stats from settings', function () {
    $settings = app(GeneralSettings::class);
    $settings->homepage_stats = [
        ['label' => 'Tecrübe', 'value' => '5+ yıl'],
    ];
    $settings->save();

    $this->get(route('home'))
        ->assertSee('Tecrübe', escape: false)
        ->assertSee('5+ yıl', escape: false);
});

it('does not render the projects section on the home page', function () {
    Project::factory()->count(3)->create();

    $this->get(route('home'))
        ->assertDontSee('>Projeler</h2>', escape: false)
        ->assertDontSee(route('projects.show', Project::first()), escape: false);
});

it('renders testimonials as a looping marquee with duplicated items', function () {
    $testimonial = Testimonial::factory()->create(['body' => 'Harika bir iş çıkardı, kesinlikle tavsiye ederim.']);

    $response = $this->get(route('home'));

    $response->assertSee('data-marquee', escape: false);

    expect(substr_count($response->getContent(), e($testimonial->body)))->toBe(2);
});

it('renders the call-to-action block', function () {
    $this->get(route('home'))
        ->assertSee('Bir proje fikrin var mı?', escape: false)
        ->assertSee('data-cta', escape: false);
});

it('shows post dates with Turkish month names', function () {
    Post::factory()->published()->create([
        'published_at' => Carbon::create(2026, 9, 16, 12),
    ]);

    $this->get(route('home'))
        ->assertSee('16 Eyl 2026', escape: false)
        ->assertDontSee('16 Sep 2026', escape: false);
});

it('runs in the Istanbul timezone with Turkish as the default locale', function () {
    expect(config('app.timezone'))->toBe('Europe/Istanbul')
        ->and(config('app.locale'))->toBe('tr')
        ->and(config('app.fallback_locale'))->toBe('tr');
});
