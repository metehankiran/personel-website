<?php

use App\Models\Testimonial;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('testimonial can be created with valid attributes', function () {
    $testimonial = Testimonial::factory()->create();

    expect($testimonial)->toBeInstanceOf(Testimonial::class)
        ->and($testimonial->name)->toBeString()
        ->and($testimonial->title)->toBeString()
        ->and($testimonial->body)->toBeString();
});

test('testimonial avatar is nullable', function () {
    $testimonial = Testimonial::factory()->create(['avatar' => null]);

    expect($testimonial->avatar)->toBeNull();
});

test('testimonial company is nullable', function () {
    $testimonial = Testimonial::factory()->create(['company' => null]);

    expect($testimonial->company)->toBeNull();
});

test('testimonials are ordered by sort_order', function () {
    Testimonial::factory()->create(['sort_order' => 3, 'name' => 'Third']);
    Testimonial::factory()->create(['sort_order' => 1, 'name' => 'First']);
    Testimonial::factory()->create(['sort_order' => 2, 'name' => 'Second']);

    $testimonials = Testimonial::ordered()->get();

    expect($testimonials->pluck('name')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('rating is optional and stored as an integer', function () {
    expect(Testimonial::factory()->create()->fresh()->rating)->toBeNull()
        ->and(Testimonial::factory()->rated(4)->create()->fresh()->rating)->toBe(4);
});

test('testimonial seeder runs without errors', function () {
    $this->seed(TestimonialSeeder::class);

    expect(Testimonial::count())->toBeGreaterThan(0);
});
