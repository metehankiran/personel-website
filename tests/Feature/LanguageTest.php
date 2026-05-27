<?php

use App\Models\Language;
use Database\Seeders\LanguageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('language can be created with valid attributes', function () {
    $language = Language::factory()->create();

    expect($language)->toBeInstanceOf(Language::class)
        ->and($language->name)->toBeString()
        ->and($language->level)->toBeString();
});

test('languages are ordered by sort_order', function () {
    Language::factory()->create(['sort_order' => 3, 'name' => 'Third']);
    Language::factory()->create(['sort_order' => 1, 'name' => 'First']);
    Language::factory()->create(['sort_order' => 2, 'name' => 'Second']);

    $languages = Language::ordered()->get();

    expect($languages->pluck('name')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('language seeder runs without errors', function () {
    $this->seed(LanguageSeeder::class);

    expect(Language::count())->toBeGreaterThan(0);
});
