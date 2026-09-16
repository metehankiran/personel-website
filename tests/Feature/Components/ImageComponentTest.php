<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

it('renders the default cover when the source is missing and swaps on load errors', function () {
    $html = (string) $this->blade('<x-image :src="null" fallback="cover" alt="Kapak" class="w-full" />');

    expect($html)
        ->toContain('src="'.asset('images/default-cover.svg').'"')
        ->toContain('alt="Kapak"')
        ->toContain('class="w-full"')
        ->toContain('onerror=')
        ->toContain('loading="lazy"');
});

it('renders the stored file when it exists', function () {
    Storage::disk('public')->put('avatars/a.png', 'png');

    $html = (string) $this->blade('<x-image src="avatars/a.png" fallback="avatar" alt="Avatar" />');

    expect($html)
        ->toContain('src="'.Storage::url('avatars/a.png').'"')
        ->toContain(asset('images/default-avatar.svg'));
});
