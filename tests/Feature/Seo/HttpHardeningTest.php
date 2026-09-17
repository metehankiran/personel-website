<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://example.test']);
});

it('sends the baseline security headers on public pages', function () {
    $this->get('https://example.test/')
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
});

it('sends them on the admin panel and on error pages too', function () {
    $this->actingAs(User::factory()->create())->get('https://example.test/admin')->assertHeader('X-Content-Type-Options', 'nosniff');

    $this->get('https://example.test/yok-boyle-bir-sayfa')->assertNotFound()->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});

it('only promises https to browsers when the request really is https', function () {
    $this->get('https://example.test/')->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

    $this->get('http://example.test/')->assertHeaderMissing('Strict-Transport-Security');
});

it('permanently redirects the www host to the configured host, keeping path and query', function () {
    $this->get('https://www.example.test/hizmetler?service=3')
        ->assertStatus(301)
        ->assertRedirect('https://example.test/hizmetler?service=3');

    $this->get('https://www.example.test/')->assertStatus(301)->assertRedirect('https://example.test');
});

it('leaves every other host alone', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    'the configured host' => 'https://example.test/',
    'a local address' => 'http://127.0.0.1:8000/',
    'an unrelated host' => 'https://staging.example.test/',
]);

it('redirects the bare host when the site is configured with www', function () {
    config(['app.url' => 'https://www.example.test']);

    $this->get('https://example.test/blog')->assertStatus(301)->assertRedirect('https://www.example.test/blog');
});
