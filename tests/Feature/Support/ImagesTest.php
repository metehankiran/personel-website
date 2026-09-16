<?php

declare(strict_types=1);

use App\Support\Images;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

it('falls back to the default cover when the path is empty', function () {
    expect(Images::url(null, 'cover'))->toBe(asset('images/default-cover.svg'))
        ->and(Images::url('', 'cover'))->toBe(asset('images/default-cover.svg'));
});

it('falls back to the default avatar when the file does not exist on disk', function () {
    expect(Images::url('avatars/missing.png', 'avatar'))->toBe(asset('images/default-avatar.svg'));
});

it('returns the storage url when the file exists', function () {
    Storage::disk('public')->put('covers/real.png', 'png');

    expect(Images::url('covers/real.png', 'cover'))->toBe(Storage::url('covers/real.png'));
});

it('passes absolute urls through untouched', function () {
    expect(Images::url('https://cdn.example.com/a.png', 'cover'))->toBe('https://cdn.example.com/a.png');
});

it('reports whether a stored image exists', function () {
    Storage::disk('public')->put('brands/x.png', 'png');

    expect(Images::exists('brands/x.png'))->toBeTrue()
        ->and(Images::exists('brands/y.png'))->toBeFalse()
        ->and(Images::exists(null))->toBeFalse();
});

it('uses the png default for open graph images', function () {
    expect(Images::og(null))->toBe(asset('images/og-default.png'))
        ->and(Images::og('covers/missing.png'))->toBe(asset('images/og-default.png'));

    Storage::disk('public')->put('covers/real.png', 'png');

    expect(Images::og('covers/real.png'))->toBe(Storage::url('covers/real.png'));
});

it('ships the default image assets in the public directory', function () {
    expect(public_path('images/default-cover.svg'))->toBeFile()
        ->and(public_path('images/default-avatar.svg'))->toBeFile()
        ->and(public_path('images/og-default.png'))->toBeFile();
});
