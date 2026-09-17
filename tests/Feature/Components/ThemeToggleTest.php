<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('records the chosen mode on the html element before the first paint', function () {
    $head = Str::before($this->get(route('home'))->getContent(), '</head>');

    expect($head)->toContain("localStorage.getItem('mk-theme')")
        ->toContain("setAttribute('data-theme-mode'")
        ->toContain("setAttribute('data-theme'");
});

it('treats an unknown stored value as the system mode', function () {
    $head = Str::before($this->get(route('home'))->getContent(), '</head>');

    expect($head)->toContain("['light', 'dark', 'system']");
});

it('ships the toggle buttons in their resting state, as radios', function () {
    $html = $this->get(route('home'))->getContent();

    preg_match_all('/<button type="button" data-theme-set="(light|system|dark)"[^>]*>/', $html, $matches);

    expect($matches[0])->not->toBeEmpty();

    foreach ($matches[0] as $button) {
        expect($button)->toContain('role="radio"')
            ->toContain('aria-checked="false"')
            ->toContain('theme-radio')
            // The active look must come from CSS, never from classes that a late script adds.
            ->not->toContain('bg-white')
            ->not->toContain('shadow-sm');
    }
});

it('styles the active button from the html attribute in css', function () {
    $css = file_get_contents(resource_path('css/app.css'));

    foreach (['light', 'system', 'dark'] as $mode) {
        expect($css)->toContain('[data-theme-mode="'.$mode.'"] .theme-radio[data-theme-set="'.$mode.'"]');
    }
});

it('no longer paints the buttons by toggling classes from the deferred script', function () {
    $script = file_get_contents(public_path('theme/js/main.js'));

    expect($script)->not->toContain('activeClass')
        ->not->toContain('inactiveClass')
        ->toContain("setAttribute('data-theme-mode'")
        ->toContain("setAttribute('aria-checked'");
});
