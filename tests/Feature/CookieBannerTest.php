<?php

declare(strict_types=1);

use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cookie banner script and config are present on every page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('mkCookieConfig', false);
});

test('cookie config contains correct policy urls', function () {
    $general = app(GeneralSettings::class);
    $general->cookie_policy_slug = 'cookie-policy';
    $general->kvkk_page_slug = 'kvkk';
    $general->save();

    $response = $this->get('/');

    $response->assertSee('cookiePolicyUrl', false);
    $response->assertSee('kvkkUrl', false);
    $response->assertSee('/pages/cookie-policy', false);
    $response->assertSee('/pages/kvkk', false);
});

test('cookie config handles missing policy slugs gracefully', function () {
    $general = app(GeneralSettings::class);
    $general->cookie_policy_slug = null;
    $general->kvkk_page_slug = null;
    $general->save();

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('cookiePolicyUrl', false);
});
