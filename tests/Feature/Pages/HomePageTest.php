<?php

declare(strict_types=1);

use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
