<?php

declare(strict_types=1);

use App\Models\Page;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * @return array{0: Page, 1: Page}
 */
function createLegalPages(): array
{
    $kvkk = Page::factory()->published()->create(['title' => 'KVKK', 'slug' => 'kvkk']);
    $cookies = Page::factory()->published()->create(['title' => 'Çerezler', 'slug' => 'cookie-policy']);

    $settings = app(GeneralSettings::class);
    $settings->kvkk_page_slug = 'kvkk';
    $settings->cookie_policy_slug = 'cookie-policy';
    $settings->save();

    return [$kvkk, $cookies];
}

it('clears the setting when its page is deleted', function () {
    [$kvkk] = createLegalPages();

    $kvkk->delete();

    $settings = app(GeneralSettings::class)->refresh();

    expect($settings->kvkk_page_slug)->toBeNull()
        ->and($settings->cookie_policy_slug)->toBe('cookie-policy');
});

it('follows the page when its slug changes', function () {
    [, $cookies] = createLegalPages();

    $cookies->update(['slug' => 'cerez-politikasi']);

    expect(app(GeneralSettings::class)->refresh()->cookie_policy_slug)->toBe('cerez-politikasi');
});

it('leaves the settings alone when an unrelated page is deleted', function () {
    createLegalPages();

    Page::factory()->create(['slug' => 'other'])->delete();

    $settings = app(GeneralSettings::class)->refresh();

    expect($settings->kvkk_page_slug)->toBe('kvkk')
        ->and($settings->cookie_policy_slug)->toBe('cookie-policy');
});

it('resolves public urls only for existing published pages', function () {
    Page::factory()->published()->create(['slug' => 'live']);
    Page::factory()->create(['slug' => 'draft']);

    expect(Page::publicUrl('live'))->toBe(route('pages.show', 'live'))
        ->and(Page::publicUrl('draft'))->toBeNull()
        ->and(Page::publicUrl('missing'))->toBeNull()
        ->and(Page::publicUrl(null))->toBeNull();
});

it('gives the cookie banner links for both legal pages', function () {
    createLegalPages();

    $this->get(route('home'))
        ->assertSee('cookiePolicyUrl: "'.route('pages.show', 'cookie-policy').'"', escape: false)
        ->assertSee('kvkkUrl: "'.route('pages.show', 'kvkk').'"', escape: false);
});

it('drops a deleted page from the cookie banner config', function () {
    [, $cookies] = createLegalPages();

    $cookies->delete();

    $this->get(route('home'))
        ->assertSee('cookiePolicyUrl: null', escape: false)
        ->assertSee('kvkkUrl: "'.route('pages.show', 'kvkk').'"', escape: false);
});

it('drops an unpublished page from the cookie banner config', function () {
    [$kvkk] = createLegalPages();

    $kvkk->update(['is_published' => false]);

    $this->get(route('home'))->assertSee('kvkkUrl: null', escape: false);
});

it('never points the banner at a stale slug', function () {
    $settings = app(GeneralSettings::class);
    $settings->cookie_policy_slug = 'ghost';
    $settings->save();

    $this->get(route('home'))
        ->assertSee('cookiePolicyUrl: null', escape: false)
        ->assertDontSee('/pages/ghost', escape: false);
});

it('builds the banner sentence from the available links only', function () {
    $script = file_get_contents(public_path('theme/js/cookie.js'));

    expect($script)->not->toContain("|| '#'")
        ->and($script)->toContain('cfg.cookiePolicyUrl')
        ->and($script)->toContain('cfg.kvkkUrl');
});
