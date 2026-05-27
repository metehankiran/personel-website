<?php

use App\Models\Experience;
use Database\Seeders\ExperienceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('experience can be created with valid attributes', function () {
    $experience = Experience::factory()->create();

    expect($experience)->toBeInstanceOf(Experience::class)
        ->and($experience->title)->toBeString()
        ->and($experience->company)->toBeString()
        ->and($experience->start_date)->toBeInstanceOf(Carbon::class);
});

test('experience end_date is nullable for current positions', function () {
    $experience = Experience::factory()->current()->create();

    expect($experience->end_date)->toBeNull();
});

test('experience description is nullable', function () {
    $experience = Experience::factory()->create(['description' => null]);

    expect($experience->description)->toBeNull();
});

test('experience factory can create current state', function () {
    $experience = Experience::factory()->current()->create();

    expect($experience->end_date)->toBeNull();
});

test('experiences are ordered by sort_order', function () {
    Experience::factory()->create(['sort_order' => 3, 'company' => 'Third']);
    Experience::factory()->create(['sort_order' => 1, 'company' => 'First']);
    Experience::factory()->create(['sort_order' => 2, 'company' => 'Second']);

    $experiences = Experience::ordered()->get();

    expect($experiences->pluck('company')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('experience seeder runs without errors', function () {
    $this->seed(ExperienceSeeder::class);

    expect(Experience::count())->toBeGreaterThan(0);
});
