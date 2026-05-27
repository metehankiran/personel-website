<?php

use App\Support\ImageGenerator;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('generates a placeholder image and returns storage path', function () {
    $path = ImageGenerator::placeholder(400, 300, 'Test');

    Storage::disk('public')->assertExists($path);
});

test('generated image has correct dimensions', function () {
    $path = ImageGenerator::placeholder(400, 300, 'Test');

    $fullPath = Storage::disk('public')->path($path);
    $size = getimagesize($fullPath);

    expect($size[0])->toBe(400)
        ->and($size[1])->toBe(300);
});

test('generates avatar with default square dimensions', function () {
    $path = ImageGenerator::avatar('MK');

    Storage::disk('public')->assertExists($path);

    $fullPath = Storage::disk('public')->path($path);
    $size = getimagesize($fullPath);

    expect($size[0])->toBe($size[1]);
});

test('generates image in specified directory', function () {
    $path = ImageGenerator::placeholder(200, 200, 'Logo', 'brands');

    expect($path)->toStartWith('brands/');
    Storage::disk('public')->assertExists($path);
});

test('generated file is a valid png', function () {
    $path = ImageGenerator::placeholder(100, 100, 'X');

    $fullPath = Storage::disk('public')->path($path);
    $mime = mime_content_type($fullPath);

    expect($mime)->toBe('image/png');
});
