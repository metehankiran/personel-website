<?php

declare(strict_types=1);

use App\Support\Images;
use Illuminate\Http\UploadedFile;
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

it('reads the pixel size of a raster image', function () {
    Storage::disk('public')->putFileAs('brands', UploadedFile::fake()->image('logo.png', 300, 120), 'logo.png');

    expect(Images::dimensions('brands/logo.png'))->toBe(['width' => 300, 'height' => 120]);
});

it('reads the size of an svg from its attributes or its viewBox', function (string $svg, array $expected) {
    Storage::disk('public')->put('brands/logo.svg', $svg);

    expect(Images::dimensions('brands/logo.svg'))->toBe($expected);
})->with([
    'width and height' => ['<svg xmlns="http://www.w3.org/2000/svg" width="240" height="60" viewBox="0 0 24 6"></svg>', ['width' => 240, 'height' => 60]],
    'units are dropped' => ['<svg xmlns="http://www.w3.org/2000/svg" width="120px" height="30.5px"></svg>', ['width' => 120, 'height' => 31]],
    'viewBox only' => ['<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 128"></svg>', ['width' => 512, 'height' => 128]],
    'percentages fall back to the viewBox' => ['<svg width="100%" height="100%" viewBox="0 0 90 30"></svg>', ['width' => 90, 'height' => 30]],
]);

it('has no size for files it cannot measure', function () {
    Storage::disk('public')->put('brands/broken.svg', '<svg></svg>');
    Storage::disk('public')->put('brands/notes.txt', 'hello');

    expect(Images::dimensions(null))->toBeNull()
        ->and(Images::dimensions('brands/missing.png'))->toBeNull()
        ->and(Images::dimensions('brands/broken.svg'))->toBeNull()
        ->and(Images::dimensions('brands/notes.txt'))->toBeNull()
        ->and(Images::dimensions('https://cdn.example.com/a.png'))->toBeNull();
});
