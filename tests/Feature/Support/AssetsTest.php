<?php

declare(strict_types=1);

use App\Support\Assets;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('appends the file modification time as a version query', function () {
    $modifiedAt = filemtime(public_path('theme/js/main.js'));

    expect(Assets::versioned('theme/js/main.js'))
        ->toBe(asset('theme/js/main.js').'?v='.$modifiedAt);
});

it('returns the plain asset url when the file is missing', function () {
    expect(Assets::versioned('theme/js/missing.js'))->toBe(asset('theme/js/missing.js'));
});

it('loads theme scripts versioned and deferred in the layout', function () {
    $response = $this->get(route('home'));

    foreach (['main', 'search', 'cookie'] as $script) {
        $response->assertSee(
            '<script src="'.Assets::versioned("theme/js/{$script}.js").'" defer></script>',
            escape: false,
        );
    }
});

it('applies the stored theme in the head before first paint', function () {
    $content = $this->get(route('home'))->getContent();

    $head = Str::before($content, '</head>');

    expect($head)->toContain("localStorage.getItem('mk-theme')")
        ->and($head)->toContain("setAttribute('data-theme'");
});
