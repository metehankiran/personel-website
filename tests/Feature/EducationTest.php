<?php

use App\Enums\EducationDegree;
use App\Models\Education;
use Database\Seeders\EducationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('education can be created with valid attributes', function () {
    $education = Education::factory()->create();

    expect($education)->toBeInstanceOf(Education::class)
        ->and($education->school)->toBeString()
        ->and($education->degree)->toBeInstanceOf(EducationDegree::class)
        ->and($education->field)->toBeString()
        ->and($education->start_date)->toBeInstanceOf(Carbon::class);
});

test('education degree is cast to enum', function () {
    $education = Education::factory()->create(['degree' => EducationDegree::Bachelor]);

    expect($education->fresh()->degree)->toBe(EducationDegree::Bachelor);
});

test('education end_date is nullable for ongoing studies', function () {
    $education = Education::factory()->ongoing()->create();

    expect($education->end_date)->toBeNull();
});

test('education gpa is nullable', function () {
    $education = Education::factory()->create(['gpa' => null]);

    expect($education->gpa)->toBeNull();
});

test('education gpa stores freeform string', function () {
    $education = Education::factory()->create(['gpa' => '3.25/4']);

    expect($education->fresh()->gpa)->toBe('3.25/4');
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
