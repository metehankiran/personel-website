<?php

use App\Models\Project;
use App\Models\ProjectCategory;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('project category can be created', function () {
    $category = ProjectCategory::factory()->create();

    expect($category)->toBeInstanceOf(ProjectCategory::class)
        ->and($category->name)->toBeString()
        ->and($category->slug)->toBeString();
});

test('project can be created with valid attributes', function () {
    $project = Project::factory()->create();

    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->title)->toBeString()
        ->and($project->slug)->toBeString()
        ->and($project->description)->toBeString()
        ->and($project->year)->toBeInt();
});

test('project belongs to a category', function () {
    $category = ProjectCategory::factory()->create();
    $project = Project::factory()->for($category, 'category')->create();

    expect($project->category)->toBeInstanceOf(ProjectCategory::class)
        ->and($project->category->id)->toBe($category->id);
});

test('category has many projects', function () {
    $category = ProjectCategory::factory()
        ->has(Project::factory()->count(3))
        ->create();

    expect($category->projects)->toHaveCount(3);
});

test('project stats are stored as json', function () {
    $stats = [
        ['label' => 'Aktif Kiracı', 'value' => '200+'],
        ['label' => 'Aylık İstek', 'value' => '2.4M'],
    ];

    $project = Project::factory()->create(['stats' => $stats]);

    expect($project->fresh()->stats)->toBe($stats);
});

test('project cover_image is nullable', function () {
    $project = Project::factory()->create(['cover_image' => null]);

    expect($project->cover_image)->toBeNull();
});

test('project client is nullable', function () {
    $project = Project::factory()->create(['client' => null]);

    expect($project->client)->toBeNull();
});

test('projects are ordered by sort_order', function () {
    Project::factory()->create(['sort_order' => 3, 'title' => 'Third']);
    Project::factory()->create(['sort_order' => 1, 'title' => 'First']);
    Project::factory()->create(['sort_order' => 2, 'title' => 'Second']);

    $projects = Project::ordered()->get();

    expect($projects->pluck('title')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('project seeder runs without errors', function () {
    $this->seed(ProjectSeeder::class);

    expect(Project::count())->toBeGreaterThan(0)
        ->and(ProjectCategory::count())->toBeGreaterThan(0);
});
