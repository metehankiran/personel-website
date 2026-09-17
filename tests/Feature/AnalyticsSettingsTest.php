<?php

declare(strict_types=1);

use App\Settings\SeoSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function seoSettings(array $values): void
{
    $settings = app(SeoSettings::class);

    foreach ($values as $key => $value) {
        $settings->{$key} = $value;
    }

    $settings->save();
}

it('prints the search console verification tag when a code is configured', function () {
    seoSettings(['google_search_console_id' => 'abc123-verification']);

    $this->get(route('home'))
        ->assertSee('<meta name="google-site-verification" content="abc123-verification">', escape: false);
});

it('omits the verification tag when no code is configured', function (?string $value) {
    seoSettings(['google_search_console_id' => $value]);

    $this->get(route('home'))->assertDontSee('google-site-verification', escape: false);
})->with([null, '']);

it('hands the analytics id to the cookie banner config', function () {
    seoSettings(['google_analytics_id' => 'G-TEST12345']);

    $this->get(route('home'))->assertSee('analyticsId: "G-TEST12345"', escape: false);
});

it('passes a null analytics id when none is configured', function (?string $value) {
    seoSettings(['google_analytics_id' => $value]);

    $this->get(route('home'))->assertSee('analyticsId: null', escape: false);
})->with([null, '']);

it('never loads google analytics from the server rendered html', function () {
    seoSettings(['google_analytics_id' => 'G-TEST12345']);

    // The tag is injected by cookie.js only after the visitor accepts analytics cookies.
    $this->get(route('home'))->assertDontSee('googletagmanager.com', escape: false);
});

it('defines the analytics loader that the consent banner calls', function () {
    $script = file_get_contents(public_path('theme/js/cookie.js'));

    expect($script)->toContain('window.mkEnableAnalytics = function')
        ->and($script)->toContain('googletagmanager.com/gtag/js');
});
