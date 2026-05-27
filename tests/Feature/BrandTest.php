<?php

use App\Models\Brand;
use Database\Seeders\BrandSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('brand can be created with valid attributes', function () {
    $brand = Brand::factory()->create();

    expect($brand)->toBeInstanceOf(Brand::class)
        ->and($brand->name)->toBeString();
});

test('brand logo is nullable', function () {
    $brand = Brand::factory()->create(['logo' => null]);

    expect($brand->logo)->toBeNull();
});

test('brand url is nullable', function () {
    $brand = Brand::factory()->create(['url' => null]);

    expect($brand->url)->toBeNull();
});

test('brands are ordered by sort_order', function () {
    Brand::factory()->create(['sort_order' => 3, 'name' => 'Third']);
    Brand::factory()->create(['sort_order' => 1, 'name' => 'First']);
    Brand::factory()->create(['sort_order' => 2, 'name' => 'Second']);

    $brands = Brand::ordered()->get();

    expect($brands->pluck('name')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('brand seeder runs without errors', function () {
    $this->seed(BrandSeeder::class);

    expect(Brand::count())->toBeGreaterThan(0);
});
