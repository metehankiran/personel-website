<?php

declare(strict_types=1);

use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function contactRows(): array
{
    $html = test()->get(route('contact'))->assertOk()->getContent();

    preg_match_all('#<(a|div) [^>]*data-contact-row="([a-z]+)".*?</\1>#s', $html, $matches, PREG_SET_ORDER);

    return collect($matches)->mapWithKeys(fn (array $m): array => [$m[2] => ['tag' => $m[1], 'html' => $m[0]]])->all();
}

beforeEach(function () {
    $general = app(GeneralSettings::class);
    $general->author_email = 'ada@example.test';
    $general->author_phone = '+90 555 000 00 00';
    $general->author_address = 'Atatürk Blv. No:1, Kütahya';
    $general->save();

    $social = app(SocialSettings::class);
    $social->github_url = 'https://github.com/ada';
    $social->save();
});

it('starts every contact row with an icon', function () {
    $rows = contactRows();

    expect(array_keys($rows))->toBe(['email', 'phone', 'github', 'address']);

    expect($rows['email']['html'])->toContain('data-lucide="mail"')
        ->and($rows['phone']['html'])->toContain('data-lucide="phone"')
        ->and($rows['address']['html'])->toContain('data-lucide="map-pin"')
        ->and($rows['github']['html'])->toContain('<svg');

    foreach ($rows as $row) {
        // the leading icon comes before the label
        expect(strpos($row['html'], 'data-contact-row-icon'))->toBeLessThan(strpos($row['html'], 'uppercase'));
    }
});

it('ends every clickable row with the same arrow', function () {
    foreach (['email', 'phone', 'github'] as $key) {
        $row = contactRows()[$key];

        expect($row['tag'])->toBe('a')
            ->and(substr_count($row['html'], 'data-lucide="arrow-up-right"'))->toBe(1);
    }
});

it('does not promise a click on an address that has no map link', function () {
    $row = contactRows()['address'];

    expect($row['tag'])->toBe('div')->and($row['html'])->not->toContain('arrow-up-right');
});

it('turns the address into a link with the arrow once a map url is set', function () {
    $general = app(GeneralSettings::class);
    $general->google_maps_url = 'https://maps.app.goo.gl/example';
    $general->save();

    $row = contactRows()['address'];

    expect($row['tag'])->toBe('a')
        ->and($row['html'])->toContain('href="https://maps.app.goo.gl/example"')
        ->toContain('data-lucide="arrow-up-right"')
        ->toContain('Atatürk Blv. No:1, Kütahya');
});

it('opens external profiles and the map in a new tab, but not mail and phone links', function () {
    $general = app(GeneralSettings::class);
    $general->google_maps_url = 'https://maps.app.goo.gl/example';
    $general->save();

    $rows = contactRows();

    expect($rows['github']['html'])->toContain('target="_blank"')->toContain('rel="noopener"')
        ->and($rows['address']['html'])->toContain('target="_blank"')
        ->and($rows['email']['html'])->not->toContain('target="_blank"')
        ->and($rows['phone']['html'])->not->toContain('target="_blank"');
});
