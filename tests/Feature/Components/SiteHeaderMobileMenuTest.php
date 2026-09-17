<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function mobileMenuTag(): string
{
    preg_match('/<div id="mobile-menu"[^>]*>/', test()->get(route('home'))->getContent(), $matches);

    return $matches[0] ?? '';
}

it('opens the mobile menu over the page instead of pushing the content down', function () {
    expect(mobileMenuTag())
        // Taken out of the flow and hung below the sticky header, so the header keeps its height.
        ->toContain('absolute')
        ->toContain('top-full')
        ->toContain('inset-x-0')
        // The header is translucent; the panel needs its own background to cover the page behind it.
        ->toContain('bg-white')
        ->toContain('dark:bg-neutral-950');
});

it('scrolls inside the mobile menu when it is taller than the screen', function () {
    $html = $this->get(route('home'))->getContent();

    expect($html)->toMatch('/<nav class="[^"]*max-h-\[calc\(100dvh-5rem\)\][^"]*overflow-y-auto[^"]*" aria-label="Mobil menü">/');
});

it('closes the mobile menu with escape and with a click outside the header', function () {
    $html = $this->get(route('home'))->getContent();

    expect($html)->toContain("event.key === 'Escape'")
        // Opening the menu re-renders the icons, which detaches the clicked node; only the composed path still knows it was inside the header.
        ->toContain('event.composedPath().includes(header)');
});
