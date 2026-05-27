<?php

use App\Models\Education;
use Database\Seeders\EducationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('education can be created with valid attributes', function () {
    $education = Education::factory()->create();

    expect($education)->toBeInstanceOf(Education::class)
        ->and($education->school)->toBeString()
        ->and($education->degree)->toBeString()
        ->and($education->field)->toBeString()
        ->and($education->start_date)->toBeInstanceOf(Carbon::class);
});

test('education end_date is nullable for ongoing studies', function () {
    $education = Education::factory()->ongoing()->create();

    expect($education->end_date)->toBeNull();
});

test('education description is nullable', function () {
    $education = Education::factory()->create(['description' => null]);

    expect($education->description)->toBeNull();
});

test('educations are ordered by sort_order', function () {
    Education::factory()->create(['sort_order' => 3, 'school' => 'Third']);
    Education::factory()->create(['sort_order' => 1, 'school' => 'First']);
    Education::factory()->create(['sort_order' => 2, 'school' => 'Second']);

    $educations = Education::ordered()->get();

    expect($educations->pluck('school')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('education seeder runs without errors', function () {
    $this->seed(EducationSeeder::class);

    expect(Education::count())->toBeGreaterThan(0);
});
