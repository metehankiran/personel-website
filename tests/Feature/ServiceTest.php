<?php

use App\Models\Service;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('service can be created with valid attributes', function () {
    $service = Service::factory()->create();

    expect($service)->toBeInstanceOf(Service::class)
        ->and($service->title)->toBeString()
        ->and($service->description)->toBeString()
        ->and($service->features)->toBeArray()
        ->and($service->pricing)->toBeString();
});

test('service badge is nullable', function () {
    $service = Service::factory()->create(['badge' => null]);

    expect($service->badge)->toBeNull();
});

test('service duration is nullable', function () {
    $service = Service::factory()->create(['duration' => null]);

    expect($service->duration)->toBeNull();
});

test('services are ordered by sort_order', function () {
    Service::factory()->create(['sort_order' => 3, 'title' => 'Third']);
    Service::factory()->create(['sort_order' => 1, 'title' => 'First']);
    Service::factory()->create(['sort_order' => 2, 'title' => 'Second']);

    $services = Service::ordered()->get();

    expect($services->pluck('title')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('service seeder runs without errors', function () {
    $this->seed(ServiceSeeder::class);

    expect(Service::count())->toBeGreaterThan(0);
});
